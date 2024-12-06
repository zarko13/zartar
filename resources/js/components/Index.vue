<template>
    <div class="flex flex-row">
        <div class="basis-1/2">
            <div class="panel">
                <div class="nameplate">ZarTar</div>
                <div v-if="permissionsChecked" class="base">
                    <div class="lens">
                        <div class="reflections"></div>
                    </div>
                    <div class="animation"></div>
                </div>
            </div>
        </div>
        <div class="basis-1/2 max-h-[50px]" style="overflow: scroll;">
            <div class="flex items-start gap-2.5">
                <div class="flex z-100 flex-col w-full max-w-[640px] leading-1.5 p-4 border-gray-200 bg-blue-100 dark:bg-gray-700">
                    <div v-for="message in conversation" class="flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{message.is_user ? 'You:' : 'ZarTar:' }}
                            <div class="flex">
                                <div class="bg-blue-200 text-black p-2 rounded-lg max-w-xs" :class="message.is_user ? 'justify-end' : ''">{{message.text}}
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
        recognition: null,
        diagnostic: null,
        bg: null,
        conversation: [],
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
            this.addToConversation(text, false);
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
                    this.executeCommand(color);
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
            this.addToConversation(command, true);
            await axios({
                url: '/async/execute-command',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    command
                },
            }).then(async (response) => {
                console.log('mess', response.data.speak_message)
                this.speak(response.data.speak_message);
            }).catch((error) => {
                console.log(error);
                this.speak(error.response.data[0]);
            });
        },

        addToConversation: function (text, isUser) {
            this.conversation.push({
                text: text,
                is_user: isUser
            })
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
