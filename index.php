<html>

<body>


    <form class="m-3" action="upload.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <legend>Inserir um XML do Lattes</legend>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">XML Lattes</span>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="fileXML" aria-describedby="fileXML" name="file">
                <label class="custom-file-label" for="fileXML">Escolha o arquivo</label>
            </div>
        </div>
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Enviar XML</button>
        </div>
    </form>

</body>