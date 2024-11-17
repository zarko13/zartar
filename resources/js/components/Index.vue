<template>
    <div class="panel">
        <div class="nameplate">ZarTar</div>
        <div v-if="permissionsChecked" class="base">
            <div class="lens">
            <div class="reflections"></div>
            </div>
            <div class="animation"></div>
        </div>
        <div class="speaker flex">
            <span class="speaker-no-permissions" v-if="permissionsChecked && !hasPermission">
                I'm sorry, Dave. I’m afraid I can’t do that
            </span>
        </div>
    </div>

</template>

<script>
    export default {
        data: () => ({
            speaker : window.speechSynthesis,
            permissionsChecked: false,
            hasPermission : false   
        }),
        mounted: function() {
            this.checkPermissions()
        },
        methods: {
            speak: function(text) {
                this.speaker.cancel();
                this.speaker.speak(new window.SpeechSynthesisUtterance(text))
            },
            greetingSpeech:async  function() {
                this.hasPermission = true;
                this.speak('Hello')
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
            }
        }
    }
</script>