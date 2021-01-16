console.log('[AUTOCOMPLETE.JS HERE]')

let searchInput = document.getElementById('search_input')
let dataList = document.getElementById('datalistOptions')
let results;

searchInput.addEventListener('input', (e) => {
    getTagsAutocomplete(e.target.value)
})

const getTagsAutocomplete = async (query) => {
    await axios.get('/autocomplete?q=' + query)
        .then(response => response.data)
        .then(data => {
            results = data
            return data
        }, [])
    createDataList(results)
}

const createDataList = (results) => {
    dataList.innerHTML = ''
    results.map(tag => {
        const option = document.createElement('option')
        option.text = tag.title
        option.value = tag.title
        option.accessKey = tag.id
        dataList.appendChild(option)
    })
}