//----------------------------------
//---MULTIPLE AUDIO CONCATENATION---
//----------------------------------

class multiAudioPlayer{

  audios = [];
  button = {"play":null};
  msWait = 0;

  //create instance giving audio element tag/.class/#id, play button tag/.class/#id, pause time in milliseconds
  constructor(audioQuery = 'audio',playButtonQuery = 'button',msWait = 800){

    this.audios = document.querySelectorAll(audioQuery);
    this.buttons = {"play":document.querySelector(playButtonQuery)};
    this.msWait = msWait;

    this.buttons.play.addEventListener('click', async () => {
      this.buttons.play.setAttribute('disabled',true);
      for(var a of this.audios){ a.play(); await this.waitForEnd(a); await this.wait(this.msWait); }
      this.buttons.play.removeAttribute('disabled',true);
    });

  }

  //wait for audio to end
  async waitForEnd(audio) {
    return new Promise((resolve) => {
      audio.addEventListener('ended', resolve, { once: true });
    });
  }

  //wait time after each reading, in milliseconds
  async wait(ms = 800) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }

}
	
