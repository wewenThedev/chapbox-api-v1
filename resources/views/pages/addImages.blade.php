<!--DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'image</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="media">Media : </label>
    <input type="file" name="media" id="media">

    <button action="" type="submit">Enregistrer</button>
    </form>
</body>
</html-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une méthode de paiement au système Chapbox</title>
</head>
<body>
<form action="{{ route('payment-methods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nom du moyen de paiement : </label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
    
        <div class="form-group">
            <label for="description">Description : </label>
            <input type="text" name="description" class="form-control" id="description">
        </div>
    
        <div class="form-group">
            <label for="terms_conditions">Termes et conditions de l'accord :</label>
            <textarea name="terms_conditions" class="form-control" id="terms_conditions"></textarea>
            <!--input type="text" name="terms_conditions" class="form-control" id="terms_conditions"-->
        </div>
    
        <div class="form-group">
            <label for="fees">Frais de transaction</label>
            <input type="numeric" name="fees" class="form-control" id="fees" required>
    
        <div class="form-group">
            <label for="name">Logo</label>
            <input type="file" name="logo_id" id="logo_id">
        </div>
        

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</body>
</html>