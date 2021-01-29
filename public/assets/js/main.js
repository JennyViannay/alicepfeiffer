console.log('[MAIN.JS HERE]')

// Flash Message closing
var alertList = document.querySelectorAll('.alert')
alertList.forEach(function (alert) {
    new bootstrap.Alert(alert)
})

// Footer insert social media links
let smList = document.getElementById('js-social-medias')
axios.get('https://localhost:8000/ajax/social-medias')
.then(response => response.data)
.then(data => {
    data.map(socialMedia => {
        const link = document.createElement('a');
        if (socialMedia.name == 'facebook') {
            link.innerHTML = `<i class="fab fa-${socialMedia.name}-square"></i>`
        } else {
            link.innerHTML = `<i class="fab fa-${socialMedia.name}"></i>`
        }
        link.href = socialMedia.link
        link.target = '_blank'
        link.classList = 'nav-link'
        smList.appendChild(link)
    })
})