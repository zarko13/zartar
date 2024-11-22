<template>
    <div class="panel">
        <div class="nameplate">ZarTar</div>
        <div v-if="permissionsChecked" class="base">
            <div class="lens">
                <div class="reflections"></div>
            </div>
            <div class="animation"></div>
        </div>
        <span class="output"></span>
        <div v-on:click="test()" class="speaker flex">
            <span class="speaker-no-permissions" v-if="permissionsChecked && !hasPermission">
                I'm sorry, Dave. I'm afraid I can't do that.
            </span>
        </div>
        <span class="output">{{ diagnostic }}</span>
        <button type="button" v-on:click="test" class="listen-button">
                    Hold to Speak
        </button>
    </div>
</template>

<script>
export default {
    data: () => ({
        speaker: window.speechSynthesis,
        permissionsChecked: false,
        isRecognitionActive: false,
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

        await this.checkPermissions();

        await this.initializeSpeechRecognition();

        // this.button = document.querySelector('.listen-button');
        // this.button.addEventListener('mousedown', this.startRecognition);
        // this.button.addEventListener('mouseup', this.stopRecognition);
        // this.button.addEventListener('mouseleave', this.stopRecognition);
    },
    methods: {
        speak: function(text) {
            this.speaker.cancel();
            this.speaker.speak(new window.SpeechSynthesisUtterance(text));
        },
        greetingSpeech:async function() {
            this.hasPermission = true;
            this.speak('Hello');
        },
        setPermissionsToChecked:async  function() {
            this.permissionsChecked = true;
        },
        checkPermissions:async function() {
                navigator.mediaDevices
                        .getUserMedia({ audio: true, video: false })
                        .then(()=> {
                            this.setPermissionsToChecked();
                            this.greetingSpeech();
                        }).catch(err => {
                            this.setPermissionsToChecked();
                        });
            },
        initializeSpeechRecognition:async function() {
            try {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                const SpeechGrammarList = window.SpeechGrammarList || window.webkitSpeechGrammarList;

                const recognition = new SpeechRecognition();
                const speechRecognitionList = new SpeechGrammarList();

                speechRecognitionList.addFromString(this.grammar, 1);
                recognition.grammars = speechRecognitionList;
                recognition.continuous = false;
                recognition.lang = "en-US";
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                recognition.onstart = () => {
                    this.isRecognitionActive = true;
                    console.log("Recognition started.");
                };
                recognition.onend = () => {
                    this.isRecognitionActive = false;
                    console.log("Recognition ended.");
                };

                recognition.onresult = (event) => {
                    console.log(event.results);
                    const color = event.results[0][0].transcript;
                    this.speak('Your command: ' + color);
                    this.diagnostic = `Result received: ${color}`;
                    document.body.style.backgroundColor = color;
                };

                recognition.onerror = (event) => {
                    console.error("Speech recognition error:", event.error);
                    this.diagnostic = `Error: ${event.error}`;
                };

                this.recognition = recognition;
            } catch (error) {
                console.error("Speech Recognition not supported in this browser.", error);
            }
        },
        startRecognition: function() {
            console.log("TU");
            if (!this.isRecognitionActive && this.recognition) {
                this.speak('Ready to receive your command.');
                this.recognition.start();
            }
        },
        stopRecognition: function() {
            if (this.isRecognitionActive && this.recognition) {
                this.recognition.stop();
            }
        },

        test: function(){
            console.log('dsadad');
            alett('aaaaa');
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
