document.querySelectorAll('a.js-like').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault()
        var spanCount = link.querySelector('span.js-link-count')
        var spanLabel = link.querySelector('span.js-link-label')
        var icone = link.querySelector('i')
        axios.get(link.href)
        .then(response => {
            spanCount.textContent = response.data.likes
            if(icone.classList.contains('fas')){
                icone.classList.replace('fas', 'far')
                spanLabel.textContent = "J'AIME"
            } else {
                icone.classList.replace('far', 'fas')
                spanLabel.textContent = "JE N'AIME PLUS"
            }
        }).catch(error => {
            if(error.response.status === 403) {
                window.alert("Vous ne pouvez pas liker un article si vous n'êtes pas connecté !")
            }
        })
    })
})