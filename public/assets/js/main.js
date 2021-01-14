console.log('[MAIN.JS HERE]')

// Flash Message closing
var alertList = document.querySelectorAll('.alert')
alertList.forEach(function (alert) {
    new bootstrap.Alert(alert)
})

// Footer insert social media links
let smList = document.getElementById('js-social-medias')
axios.get('social-medias')
.then(response => response.data)
.then(data => {
    data.map(socialMedia => {
        const link = document.createElement('a');
        link.innerHTML = `<i class="fab fa-${socialMedia.name} fa-2x"></i>`
        link.href = socialMedia.link
        link.target = '_blank'
        link.classList = 'nav-link'
        smList.appendChild(link)
    })
})