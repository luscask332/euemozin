document.addEventListener("DOMContentLoaded", function () {
    const uploadForm = document.getElementById("uploadForm");
    const album = document.getElementById("album");

    uploadForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(uploadForm);

        fetch("upload.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const photoDiv = document.createElement("div");
                photoDiv.className = "photo-item";

                const imgElement = document.createElement("img");
                imgElement.src = "uploads/" + data.filename;

                const description = document.createElement("p");
                description.textContent = formData.get("descricao");
                description.className = "description";

                const deleteButton = document.createElement("button");
                deleteButton.textContent = "Excluir";
                deleteButton.className = "delete-button";
                deleteButton.onclick = function () {
                    excluirFoto(data.filename);
                };

                photoDiv.appendChild(imgElement);
                photoDiv.appendChild(description);
                photoDiv.appendChild(deleteButton);

                album.appendChild(photoDiv);

                uploadForm.reset();
            } else {
                alert(data.message);
            }
        });
    });

    function carregarImagens() {
        fetch("listar_imagens.php")
        .then(response => response.json())
        .then(data => {
            album.innerHTML = "";
            data.forEach(imagem => {
                const photoDiv = document.createElement("div");
                photoDiv.className = "photo-item";

                const imgElement = document.createElement("img");
                imgElement.src = "uploads/" + imagem.filename;

                const description = document.createElement("p");
                description.textContent = imagem.descricao;
                description.className = "description";

                const deleteButton = document.createElement("button");
                deleteButton.textContent = "Excluir";
                deleteButton.className = "delete-button";
                deleteButton.onclick = function () {
                    excluirFoto(imagem.filename);
                };

                photoDiv.appendChild(imgElement);
                photoDiv.appendChild(description);
                photoDiv.appendChild(deleteButton);

                album.appendChild(photoDiv);
            });
        });
    }

    function excluirFoto(filename) {
        fetch("excluir.php", {
            method: "POST",
            body: JSON.stringify({ filename: filename }),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                carregarImagens();
            } else {
                alert(data.message);
            }
        });
    }

    carregarImagens();
});
