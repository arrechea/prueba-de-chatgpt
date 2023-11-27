<?php
$catalog = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getModelCatalog($model);
if (!isset($catalog_id)) {
    $catalog_id = $catalog->id ?? '';
}
$values_url = null;
if (isset($brand)) {
    $base_url = route('admin.company.fields', [
        'catalog' => $catalog_id,
        'company' => $company,
        'brand'   => $brand
    ]);
    if (!!$model->toArray()) {
        $values_url = route('admin.company.values', [
            'company' => $company,
            'id'      => $model->id ?? 0,
            'catalog' => $catalog_id,
            'brand'   => $brand
        ]);
    }
} else {
    $base_url = route('admin.company.fields', [
        'catalog' => $catalog_id,
        'company' => $company
    ]);
    if (!!$model->toArray()) {
        $values_url = route('admin.company.values', [
            'company' => $company,
            'id'      => $model->id ?? 0,
            'catalog' => $catalog_id,
        ]);
    }
}
?>

<div hidden id="CompanyModel">{{$company ?? ''}}</div>
<div hidden id="BrandModel">{{$brand ?? ''}}</div>
<div hidden id="SpecialTextModel">{{$model ?? ''}}</div>
<div hidden
     id="SpecialTextCatalog">{{$catalog_id}}</div>

<script>
    window.SpecialTextForm = {
        urls: {
            fields_url: "{{$base_url}}",
            values_url: "{{$values_url}}"
        }
    }
</script>

<script src="{{mixGafaFit('js/admin/react/special_text_form/form/build.js')}}"></script>
