import './styles/app.scss'
import './bootstrap'

const inputTopicResponse = document.getElementById('topic_response_content')
const submitTopic        = document.getElementById('topic_response_save')
const inputFormResponce  = document.getElementById('comment_content')
const submitForm         = document.getElementById('comment_save')
const PopupLangage       = document.getElementById('PopupLangage')
const SearchButton       = document.getElementById('searchButton')
const SearchInput        = document.getElementById('searchInput')
const tableauLangage     = [
    "bite",
    "cul",
    "enculé",
    "salope",
    "putain",
    "merde",
    "pute",
    "encule"
]

const filter = (input) => {
    tableauLangage.forEach((element) => {
        let valueInput = input.value

        if (valueInput.includes(element)) {
            PopupLangage.style.display = "flex"
        }
    })
}

console.log(inputFormResponce)

if (inputTopicResponse !== null) {
    inputTopicResponse.addEventListener('keyup', () => {
        filter(inputTopicResponse)
    })

    submitTopic.addEventListener('click', () => {
        filter(inputTopicResponse)
    })
}

if (inputFormResponce !== null) {
    inputFormResponce.addEventListener('keyup', () => {
        filter(inputFormResponce)
    })

    submitForm.addEventListener('click', () => {
        filter(inputFormResponce)
    })
}

SearchButton.addEventListener('click', () => {
    let val = SearchInput.value

    document.location.href="/form/search/" + val;
})