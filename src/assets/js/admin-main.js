









// Users Page
var data_view_catalog = $('#data_view_catalog');
var data_view_table = $('#data_view_table');
var btn_view_catalog = $('#btnViewTypeCatalog');
var btn_view_table = $('#btnViewTypeTable');

btn_view_catalog.on('click', function () {
    console.log("btn_view_catalog");

    data_view_catalog.removeClass('d-none');
    data_view_table.addClass('d-none');

    btn_view_catalog.addClass('btn-primary');
    btn_view_catalog.removeClass('btn-outline-primary');
    btn_view_table.addClass('btn-outline-primary');
    btn_view_table.removeClass('btn-primary');
})

btn_view_table.on('click', function () {
    console.log("btn_view_table");

    data_view_catalog.addClass('d-none');
    data_view_table.removeClass('d-none');

    btn_view_table.addClass('btn-primary');
    btn_view_table.removeClass('btn-outline-primary');
    btn_view_catalog.addClass('btn-outline-primary');
    btn_view_catalog.removeClass('btn-primary');
})
// =================================================================