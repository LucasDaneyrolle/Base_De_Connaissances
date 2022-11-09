import './styles/app.scss'
import './bootstrap'

const inputTopicResponse = document.getElementById('topic_response_content')
const submitTopic        = document.getElementById('topic_response_save')
const inputFormResponce  = document.getElementById('comment_content')
const submitForm         = document.getElementById('comment_save')
const PopupLangage       = document.getElementById('PopupLangage')
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
    inputTopicResponse.addEventListener('keyup', (element) => {
        filter(inputTopicResponse)
    })

    submitTopic.addEventListener('click', (element) => {
        filter(inputTopicResponse)
    })
}

if (inputFormResponce !== null) {
    inputFormResponce.addEventListener('keyup', (element) => {
        filter(inputFormResponce)
    })

    submitForm.addEventListener('click', (element) => {
        filter(inputFormResponce)
    })
}