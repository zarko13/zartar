<template>
    <div class="panel">
        <div class="nameplate">ZarTar</div>
        <div v-if="permissionsChecked" class="baae">
            <div class="lens">
                <div class="reflections"></div>
            </div>
            <div class="animation"></div>
        </div>
        <span class="output"></span>
        <div class="speaker flex">
            <button
                class="listen-button"
                @mousedown="startRecognition"
                @mouseup="stopRecognition"
                @mouseleave="stopRecognition"
            >
                Hold to Speak
            </button>
        </div>
        <span class="output">{{ diagnostic }}</span>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data: () => ({
        speaker: window.speechSynthesis,
        state: 'listening',
        permissionsChecked: false,
        hasPermission: false,
        grammar:
            "#JSGF V1.0; grammar colors; public <color> = aqua | azure | beige | bisque | black | blue | brown | chocolate | coral | crimson | cyan | fuchsia | ghostwhite | gold | goldenrod | gray | green | indigo | ivory | khaki | lavender | lime | linen | magenta | maroon | moccasin | navy | olive | orange | orchid | peru | pink | plum | purple | red | salmon | sienna | silver | snow | tan | teal | thistle | tomato | turquoise | violet | white | yellow ;",
        recognition: null,
        diagnostic: null,
        bg: null,
    }),
    async mounted() {
        this.diagnostic = document.querySelector(".output");
        this.bg = document.querySelector("html");

        this.checkPermissions();
        this.initializeSpeechRecognition();
    },
    methods: {
        speak: function (text) {
            this.speaker.cancel();

            const utterance = new window.SpeechSynthesisUtterance(text);
            utterance.onend = () => {
                this.state = 'listening';
                console.log("Speech finished.");
            };

            utterance.onerror = (event) => {
                this.state = 'listening';
                console.error("Speech synthesis error:", event);
            };

            this.state = 'speaking';
            this.speaker.speak(utterance);
        },
        greetingSpeech: async function () {
            this.speak('Hello');
        },
        setPermissionsToChecked: async function () {
            this.permissionsChecked = true;
        },
        checkPermissions: async function () {
            navigator.mediaDevices
                .getUserMedia({ audio: true, video: false })
                .then(() => {
                    this.setPermissionsToChecked();
                    this.greetingSpeech();
                })
                .catch((err) => {
                    this.setPermissionsToChecked();
                });
        },
        initializeSpeechRecognition: async function () {
            try {
                const SpeechRecognition =
                    window.SpeechRecognition || window.webkitSpeechRecognition;
                const SpeechGrammarList =
                    window.SpeechGrammarList || window.webkitSpeechGrammarList;

                const recognition = new SpeechRecognition();
                const speechRecognitionList = new SpeechGrammarList();

                speechRecognitionList.addFromString(this.grammar, 1);
                recognition.grammars = speechRecognitionList;
                recognition.continuous = false;
                recognition.lang = "en-US";
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                recognition.onend = () => {
                    console.log("Recognition ended.");
                    if (this.state === 'listening') {
                        this.startRecognition();
                    }
                };

                recognition.onresult = (event) => {
                    const color = event.results[0][0].transcript.toLowerCase().trim();
                    console.log(`Detected text: ${color}`);
                    this.executeCommand(`Your command: ${color}`);
                };

                recognition.onerror = (event) => {
                    if (this.state === 'listening') {
                        this.startRecognition();
                    }
                };

                this.recognition = recognition;
            } catch (error) {
                console.error("Speech Recognition not supported in this browser.", error);
            }
        },
        startRecognition: function () {
            console.log("Starting recognition...");
            if (this.recognition) {
                this.recognition.start();
            }
        },
        stopRecognition: function () {
            console.log("Stopping recognition...");
            if (this.recognition) {
                this.recognition.stop();
            }
        },
        executeCommand: async function (command) {
            this.state = 'processing';
            await  axios({
                url: '/async/execute-command',
                method : 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    command
                },
            }).then(async (response) => {
                this.speak(response.data.message);
            }).catch((error) => {
                this.speak(error.response.data[0]);
            });
        },
    },
    watch: {
        state: function () {
            this.state === 'listening' ? this.startRecognition() : this.stopRecognition();
        },
    },
};
</script>

<style>
.listen-button {
    width: 100%;
    padding: 10px 20px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.listen-button:hover {
    background-color: #45a049;
}

.output {
    display: block;
    margin-top: 20px;
    font-size: 18px;
    color: #ff0000;
}
</style>
