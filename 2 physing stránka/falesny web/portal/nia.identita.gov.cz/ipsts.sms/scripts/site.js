function Submit(formid, doValidation) {
    /*var form = document.querySelector('#' + formid);
    if (doValidation === true && !form.checkValidity()) {
        form.classList.add('was-validated');
        return false;
    }

    var $form = $(form);
    var formData = new FormData(form);
    var checkbox = $form.find("input[type=checkbox]");

    // Combine 'tel' values with their international prefix
    $form.find('input[type=tel]').each((i, t) => {
        const pre = $(t.parentElement).find('.gov-form-control__fixed-placeholder');
        if (pre[0])
            formData.set(t.name, (pre.text() + t.value).replace(/\s/g, ''));
    });

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea, a");
    const obj = Object.fromEntries(formData.entries());

    $.each(checkbox, function (key, val) {
        //formData.append($(val).attr('name'), $(this).is(':checked'));
        obj[$(val).attr('name')] = $(this).is(':checked');
    });

    var body = JSON.stringify(obj);

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    $inputs.prop("disabled", true);
    $inputs.prop("pointer-events", 'none');

    let url = form.action;
    request = $.ajax({
        url: url,
        type: 'post',
        data: body,
        contentType: 'application/json; charset=utf-8',
        dataType: "html",
    });

    request.done(function (response, textStatus, jqXHR) {
        const parser = new DOMParser();
        const $responseDoc = parser.parseFromString(response, 'text/html');
        var mainContainer = $('<div/>').append(response).find('#event_main_container').get();
        if (mainContainer.length === 0) {
            $('#event_container').html("");
            $('#event_container').html(response);
        }
        else {
            $('#event_main_container').html("");
            $('#event_main_container').html(mainContainer[0].innerHTML);
        }
        if (typeof onSubmitDone === 'function') {
            onSubmitDone(response);
        }
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown) {
        // Log the error to the console
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });

    // Callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {
        // Reenable the inputs
        $inputs.prop("disabled", false);
    });*/
    return false;
}

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