console.log('[CONTACT.JS HERE]')

document.getElementById('form-contact').addEventListener('submit', e => {
    e.preventDefault()
    grecaptcha.ready(() => {
        grecaptcha.execute('6LfJMCQaAAAAAJfXuLTH8XpIyUbYJiFE52pMX4hO', { action: 'submit' }).then((token) => {
            console.log(token)
            axios.post('ajax/captchaverify', token)
            .then(response => {
                if (response.data === true) {
                    document.getElementById('input-token').value = token
                    document.getElementById('form-contact').submit()
                }
            })
        });
    });
})