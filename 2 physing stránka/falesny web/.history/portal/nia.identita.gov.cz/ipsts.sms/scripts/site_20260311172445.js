

function ChangeLanguage(cultureCode) {
    const d = new Date();
    d.setTime(d.getTime() + (60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = "setculture=" + cultureCode + ";" + expires + ";secure";
    location.reload();
}

function ChangeLanguageBarVisibility(visible) {
    if (visible)
        $('#ctlLanguageBar').show();
    else
        $('#ctlLanguageBar').hide();
}

var historyMap = {};

function CheckAndRepairCode() {
    const b = this.getAttribute("id");
    var h = historyMap[b] || "";
    var c = this.value.replace(otp.regEx, "");
    if (c[c.length - 1] === c[c.length - 2] && c[c.length - 1] === otp.separ) { // odebere druhy oddelovac, pokud jsou hned za sebou
        this.value = h;
        return;
    }

    var g = c.split(otp.separ);
    if (g.length > otp.grpCount) {
        this.value = h;
        return;
    }

    for (var f = 0; f < g.length; f++) {
        var d = g[f];
        var e = f <= 1 ? otp.grpLen : otp.grpLen;
        if (d.length > e) {
            this.value = h;
            return;
        }
    }

    var j = g.length;
    if (j < otp.grpCount) {
        var e = j <= 2 ? otp.grpLen : otp.grpLen;
        if (g[j - 1].length === e) {
            if (h === (c + otp.separ)) {
                c = c.substring(0, c.length - 1)
            }
            else {
                c += otp.separ;
            }
        }
    }
    this.value = c;
    historyMap[b] = c;
}

function PreventNonDigitKeys(e) {
    // povolit BCKSPC, SPC, DEL, ← → a Cislice
    // a Ctrl+C,V,X
    //console.log(e.keyCode);
    if ((e.key < '0' || e.key > '9') && e.key != ' ' && e.keyCode != 8 && e.keyCode != 46 && e.keyCode != 37 && e.keyCode != 39 &&
        e.keyCode != 13 && e.keyCode != 9 &&
        !(e.ctrlKey && (e.key == 'c' || e.key == 'v' || e.key == 'x'))) {
        return false;
    }
    return true;
}

function CheckAndFormatTel(e) {
    const inp = e.currentTarget;
    let indexS = inp.selectionStart;
    const s = ((inp.value.match(/\d/g, '') || []).join('').match(/\d{1,3}/g) || []).join(' ');
    if (e.key == ' ' && inp.selectionStart == s.length + 1) {
        s += ' ';
    }
    const val = inp.value.slice(0, inp.selectionStart);
    if ((inp.selectionStart === 4 || inp.selectionStart === 8) && val[val.length - 1] !== ' ') {
        indexS++;
    }
    inp.value = s;
    inp.setSelectionRange(indexS, indexS)
}

function markInputValid(input, isValid) {
    if (isValid)
        input.removeClass('is-invalid');
    else //if (!input.classList.contains('is-invalid'))
        input.addClass('is-invalid');
}

function onSubmitDone(response) {
    BindOpenForgottenUserShowBtn();
}

function BindOpenForgottenUserShowBtn() {
    $(".popupForgottenUserShowBtn").on("click", function () {
        $('#popupForgottenUser').removeClass("d-none");
        $('#popupForgottenUser').addClass("show d-block");
    });
}

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