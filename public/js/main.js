document.addEventListener("DOMContentLoaded", readyToProcess);
// If late; I mean on time.
if (document.readyState === "complete" ) {
    readyToProcess();
}

function readyToProcess() {
    const articles = document.getElementById('articles');
    if (articles) {
        articles.addEventListener('click', function (event){
            if (event.target.className === 'btn btn-danger delete-article') {
                if (confirm("Are you sure to delete Article")){
                    const articleId = event.target.getAttribute('data-id');

                    fetch(`/article/delete/${articleId}`, {
                        method: 'DELETE'
                    }).then(res => window.location.reload());
                }
            }
        })
    }
}
