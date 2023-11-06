let
Source = let t =
Json.Document(Web.Contents("http://localhost/laravel-dynamic-extract/public/dynamic-extract/api/v1/data/yourtoken")) in
{1..t[last_page]},
#"Converted to Table" = Table.FromList(Source, Splitter.SplitByNothing(), null, null, ExtraValues.Error),
#"Changed Type" = Table.TransformColumnTypes(#"Converted to Table",{{"Column1", type text}}),
#"Added Custom" = Table.AddColumn(#"Changed Type", "Custom", each
Json.Document(Web.Contents("http://localhost/laravel-dynamic-extract/public/dynamic-extract/api/v1/data/yourtoken?page="
&[Column1])))
in
#"Added Custom"
