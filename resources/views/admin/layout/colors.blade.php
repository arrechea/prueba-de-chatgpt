<script src="{{asset('plugins/colors/chroma.min.js')}}"></script>
<script src="{{asset('plugins/colors/tinycolor.min.js')}}"></script>
<script>
    var colors = {}, gradients = ['lighter', 'lighten'];
    var primaryColor = '{{\App\Librerias\Colors\LibColors::get('color_black',(isset($company)?$company:null))}}';
    var secondaryColor = '{{\App\Librerias\Colors\LibColors::get('color_main',(isset($company)?$company:null))}}';
    colors.primaryColor = primaryColor;
    colors.primaryColorBack = BackgroundColor(primaryColor);
    colors.primaryColorLighten = chroma(primaryColor).brighten(1.3);
    colors.primaryColorDarken = chroma(primaryColor).darken(1.3);
    colors.secondaryColor = secondaryColor;
    colors.menuBackground = BackgroundColor(primaryColor);
    colors.menuLink = TextColor(BackgroundColor(primaryColor));

    function TextColor(color){
        if(chroma(color).luminance() > 0.5 ){
           return 'black';
        } else {
          return 'white';
        }
    };
    function BackgroundColor(color){
        if(chroma(color).luminance() > 0.5 ){
           return '#3e4042';
        } else {
          return '#f5f5f5';
        }
    };

    for (var c = 0; c < gradients.length; c++){
        //colors.primarycolor + gradients[c] = chroma(primaryColor).brighten(i).hex();
    };

    var style = document.createElement('style');
    style.type = 'text/css';
    style.innerHTML =
    ':root {' +
        '--menu-background-color: ' + colors.menuBackground + ';' +
        '--menu-text-color:' + colors.menuLink + ';' +
        '--primary-color: ' + colors.primaryColor + ';' +
        '--primary-color-link: ' + colors.primaryColorLink + ';' +
        '--primary-color-back: ' + colors.primaryColorBack + ';' +
        {{-- '--primary-color-g: ' + colorToRgb(primaryColor).g + ';' +
        '--primary-color-b: ' + colorToRgb(primaryColor).b + ';' +
        '--secundary-color-r: ' + colorToRgb(secundaryColor).r + ';' +
        '--secundary-color-g: ' + colorToRgb(secundaryColor).g + ';' +
        '--secundary-color-b: ' + colorToRgb(secundaryColor).b + ';' +  --}}
    '}';
    document.getElementsByTagName('head')[0].appendChild(style);
</script>

<style>
    :root {
        --black-color: {{\App\Librerias\Colors\LibColors::get('color_black',(isset($company)?$company:null))}};
        --black-color: {{\App\Librerias\Colors\LibColors::get('color_black',(isset($company)?$company:null))}};
        --main-color: {{\App\Librerias\Colors\LibColors::get('color_main',(isset($company)?$company:null))}};
        --secondary-color: {{\App\Librerias\Colors\LibColors::get('color_secondary',(isset($company)?$company:null))}} ;
        --secondary2-color: {{\App\Librerias\Colors\LibColors::get('color_secondary2',(isset($company)?$company:null))}};
        --secondary3-color: {{\App\Librerias\Colors\LibColors::get('color_secondary3',(isset($company)?$company:null))}} ;
        --light-color: {{\App\Librerias\Colors\LibColors::get('color_light',(isset($company)?$company:null))}} ;
        --menutop-color: {{\App\Librerias\Colors\LibColors::get('color_menutop',(isset($company)?$company:null))}};
        --menuleft-color: {{\App\Librerias\Colors\LibColors::get('color_menuleft',(isset($company)?$company:null))}};
        --menuleft-secondary-color: {{\App\Librerias\Colors\LibColors::get('color_menuleft_secondary',(isset($company)?$company:null))}} ;
        --menuleft-selected-color: {{\App\Librerias\Colors\LibColors::get('color_menuleft_selected',(isset($company)?$company:null))}};
        --alert-color: {{\App\Librerias\Colors\LibColors::get('color_alert',(isset($company)?$company:null))}};
    }
</style>

