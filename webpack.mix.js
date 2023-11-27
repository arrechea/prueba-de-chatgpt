const {
    mix
} = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.js('resources/assets/js/forgeapp.js', 'public/js/forgeapp.js');
// mix.js('resources/assets/js/theme.js', 'public/js/theme.js');
// mix.js('resources/assets/js/saas-menu.js', 'public/js/saas-menu.js');

// mix.react('resources/assets/js/react/roles/create/entry.js', 'public/js/admin/react/roles/create/build.js');
// mix.react('resources/assets/js/react/admin/assignRol/entry.js', 'public/js/admin/react/admin/assignRol/build.js');
// mix.react('resources/assets/js/react/reservation/process/entryTemplate.js', 'public/js/admin/react/reservation/process/buildTemplate.js');
// mix.react('resources/assets/js/react/reservation/process/entryTemplate.js', 'public/js/buildTemplate.js');
// mix.react('resources/assets/js/react/widget/init.js', 'public/js/widget/build.js');

// mix.react('resources/assets/js/react/special_texts/list/entry.js', 'public/js/admin/react/special_texts/list/build.js');
// mix.react('resources/assets/js/react/places/selectors/entry.js', 'public/js/admin/react/places/selects/build.js');
// mix.react('resources/assets/js/react/client/secret_input/entry.js', 'public/js/admin/react/client/secret_input/build.js');
// mix.react('resources/assets/js/react/maps/generate-map/entry.js', 'public/js/admin/react/maps/generate-map/build.js');
// mix.react('resources/assets/js/react/maps/select-map-in-room/entry.js', 'public/js/admin/react/maps/select-map-in-room/build.js');
// mix.react('resources/assets/js/sdk/index.js', 'public/sdk/dist/main.js');
// mix.react('resources/assets/js/init.js', 'public/js/init.js');
// mix.react('resources/assets/js/react/special_texts/field-form/entry.js', 'public/js/admin/react/special_texts/field-form/build.js');
// mix.react('resources/assets/js/react/special_text_form/form/entry.js', 'public/js/admin/react/special_text_form/form/build.js');
// mix.react('resources/assets/js/react/items/list/entry.js', 'public/js/admin/react/items/list/build.js');

// //Css files for versions only
// mix.sass('resources/assets/sass/admin/saas/sass.scss', 'public/css/admin/saas.css');
// mix.sass('resources/assets/sass/admin/regular/app.scss', 'public/css/admin/app.css');
// mix.sass('resources/assets/sass/admin/custom.scss', 'public/css/admin/custom.css');
// mix.sass('resources/assets/sass/update/custom/custom.scss', 'public/css/admin/custom.css');
// mix.sass('resources/assets/sass/fancy/zuda/reservation-template.scss', 'public/css/admin/reservation-template.css');
// mix.sass('resources/assets/sass/fancy/original/reservation.scss', 'public/css/admin/reservation.css');
// mix.styles('public/css/admin/custom.css', 'public/css/admin/custom.css');
// mix.styles('public/css/admin/mapGenerator.css', 'public/css/admin/mapGenerator.css');
// mix.styles('public/css/admin/printCalendar.css', 'public/css/admin/printCalendar.css');
// mix.styles('public/css/admin/reservation.css', 'public/css/admin/reservation.css');
// mix.styles('public/css/admin/reservation-responsive.css', 'public/css/admin/reservation-responsive.css');
//
// //JS files for versions
// mix.scripts('public/js/common.js', 'public/js/common.js');

// mix.browserSync({
//     proxy: 'gafafit.test',
// });

if (mix.inProduction()) {
    mix.version();
}
