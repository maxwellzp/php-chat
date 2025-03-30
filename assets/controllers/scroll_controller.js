import { Controller} from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        function scrollToBottom() {
            let chatMessages = document.getElementById("messages");
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        document.addEventListener("DOMContentLoaded", scrollToBottom);

        document.addEventListener("turbo:frame-load", scrollToBottom);
    }
}
