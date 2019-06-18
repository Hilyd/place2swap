Création de l'index avec le mapping (schema.json) à la racine du dossier:

curl -XPUT -H "Content-Type: application/json" localhost:9200/place2swap -d @schema.json

Documents à mettre dans l'index

PUT place2swap/_doc
{
    "title": "Elasticsearch",
    "description": "Un article sur Elasticsearch",
    "date": "2019-06-18",
    "author": "Lydie Danjean",
    "image": "e7aca409ec5778f7fad4d3eb9512f89c.png"
}

PUT place2swap/_doc 
{
"title": "2e article",
"description": "Texte de mon deuxième article",
"date": "2019-06-18",
"author": "Lydie Danjean",
"image": "f9839800ae365f4e0b02ca5e1ed3c904.jpeg"
}

PUT place2swap/_doc
{
"title": "Titre de l'article",
"description": "Description de l'article",
"date": "2019-06-18",
"author": "Lydie Danjean",
"image": "faf7951f82558394c68b4e9b12c73510.jpeg"
}