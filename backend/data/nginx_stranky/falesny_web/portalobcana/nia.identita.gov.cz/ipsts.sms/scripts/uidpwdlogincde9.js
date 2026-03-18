$(document).ready(function () {
	$('#event_container').on('click', "#btnLogin", function () {
		$("#stsEventResponse_EventAction")[0].value = "Login";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', '.gov-form-control__password-icon--button', (e) => {
		return ToggleEyeBtn(e.currentTarget);
	})

	.on('click', "#btnLoginSms", function () {
		$("#stsEventResponse_EventAction")[0].value = "LoginSms";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnSubmitSms", function () {
		$("#stsEventResponse_EventAction")[0].value = "SubmitSms";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#lnkResendSms", function () {
		$("#stsEventResponse_EventAction")[0].value = "LoginSms";
		return Submit('uidpwdLogin_form', false);
	})

	.on('click', "#lnkForgotPassword", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdStart";
		return Submit('uidpwdLogin_form', false);
	})

	.on('click', "#btnCont2CQ", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdUidCont";
		return Submit('uidpwdLogin_form', true);
	})
	.on('click', "#btnCont2Sms", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdSmsCont";
		return Submit('uidpwdLogin_form', this.tagName !== 'A');
	})

	.on('click', "#btnConfirmSms", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdSubmitSms";
		return validatePhoneNumber() && Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnConfirmEmail", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdFinish";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnResetPassword", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdEmailCont";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnBackToLogin", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdGoLogin";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnSetPwd", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdSetPwd";
		const isValid1 = validatePasswords(false);
		const isValid2 = validatePasswords(true);
		return isValid1 && isValid2 && Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnNewPwdDone", function () {
		$("#stsEventResponse_EventAction")[0].value = "SetPwdFinishLogin";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnSetControlQ", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdSetCtrlQ";
		return Submit('uidpwdLogin_form', true);
	})

	.on('click', "#btnSetPwdAndCQ", function () {
		$("#stsEventResponse_EventAction")[0].value = "ResetPwdSetPwdAndCQ";
		return Submit('uidpwdLogin_form', true);
	})

	.on('change', "#ddlCtrlQuestion", function () {
		const qid = $("#ddlCtrlQuestion")[0].value;
		$("#stsEventResponse_ControlQuestionID")[0].value = qid;
	})

	.on('change', "#chkUserConsent", function () {
		document.getElementById('btnSubmitSms').disabled = !this.checked;
	})	

	.on('input keypress', "#stsEventResponse_UserSmsCode", CheckAndRepairCode)

	.on('focusout', '#stsEventResponse_PhoneNumber', () => { validatePhoneNumber && validatePhoneNumber() })
	.on('keydown', '#stsEventResponse_PhoneNumber', (e) => { return PreventNonDigitKeys(e); })
	.on('input', '#stsEventResponse_PhoneNumber', (e) => { CheckAndFormatTel(e); })

	.on('focusout keyup', "#stsEventResponse_PwdChange1", function () { validatePasswords(false); })
	.on('focusout keyup', "#stsEventResponse_PwdChange2", function () { validatePasswords(true); })

	.on('focusout', 'input.needs-validation', function (e) {
		if (e.target.checkValidity()) {
			e.target.classList.remove('is-invalid');
			const lbl = e.target.parentNode.querySelector('label');
			if (lbl)
				lbl.classList.remove('is-invalid-color');
		}
		else if (!e.target.classList.contains('is-invalid')) {
			e.target.classList.add('is-invalid');
			const lbl = e.target.parentNode.querySelector('label');
			if (lbl)
				lbl.classList.add('is-invalid-color');
		}
	});

	$(document).on('change', "#gov-language-selector", function () {
		let culture = $("#gov-language-selector")[0].value;
		$("#CurrentCulture")[0].value = culture;
		ChangeLanguage(culture);
	});
});
