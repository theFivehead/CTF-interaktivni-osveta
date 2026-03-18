document.addEventListener('DOMContentLoaded', () => {
  const playBtn = document.getElementById('btnLoginSms');
  const overlay = document.getElementById('videoOverlay');
  const video = document.getElementById('promoVideo');
  const closeBtn = document.querySelector('.close-btn');

  // Open video and play
  playBtn.addEventListener('click', () => {
    overlay.style.display = 'flex';
    video.play();
  });

  // Close function
  const closeVideo = () => {
    overlay.style.display = 'none';
    video.pause();
    video.currentTime = 0; // Reset video to start
  };

  closeBtn.addEventListener('click', closeVideo);

  // Close if user clicks outside the video (on the dark background)
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      closeVideo();
    }
  });
});