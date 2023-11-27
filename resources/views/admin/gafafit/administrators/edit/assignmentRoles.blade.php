<h5 class="">{{__('users.AssignRoles')}} </h5>
{{csrf_field()}}
<div id="AdminAssignRol">
    <div id="AdminAssignRol--companies" style="display:none;">{{$companies}}</div>
    <div id="AdminAssignRol--rolesGafaFit" style="display:none;">{{$rolesGafaFit}}</div>
    <div id="AdminAssignRol--adminToEdit" style="display:none;">{{$adminToEdit}}</div>
</div>
<script>
    window.RolesAsignement = {
        lang: {
            'addCompany': '{{__('users.Add')}} {{__('users.Company')}}',
            'addBrand': '{{__('users.Add')}} {{__('users.Brand')}}',
            'addLocation': '{{__('users.Add')}} {{__('users.Location')}}',
            'companies': '{{__('users.Companies')}}',
            'brands': '{{__('users.Brands')}}',
            'locations': '{{__('users.Locations')}}',
            'roles': '{{__('users.Roles')}}',
        },
        urls: {
            getAdminRoles: '{{route('admin.administrator.assignmentRoles.getRoles',[
                    'administrator'=>$adminToEdit
                    ])}}',
            getCompanyRoles: '{{route('admin.administrator.assignmentRoles.getCompanyRoles',[
                    'companyToGet'=>'|',
                    ])}}',
            getCompanyRolesAndBrands: '{{route('admin.administrator.assignmentRoles.getCompanyRolesAndBrands',[
                    'companyToGet'=>'|',
                    ])}}',
            getBrandRoles: '{{route('admin.administrator.assignmentRoles.getBrandRoles',[
                    'brandToGet'=>'|',
                    ])}}',
            getLocationRoles: '{{route('admin.administrator.assignmentRoles.getLocationRoles',[
                    'locationToGet'=>'|',
                    ])}}',
        },
        companies: JSON.parse($('#AdminAssignRol--companies').text()),
        rolesGafaFit: JSON.parse($('#AdminAssignRol--rolesGafaFit').text()),
        adminToEdit: JSON.parse($('#AdminAssignRol--adminToEdit').text())
    };
    $(document).ready(function () {
//        $('.rolesassignment select').material_select();

        (function ($) {
            var form = $('#AdminAssignRol').closest('.User--assignmentRoles');
            var yaAplicado = !!window.RolesAsignementAplicado;
            window.RolesAsignementAplicado = true;

            if (!yaAplicado) {
                form.find('.modal-close').on('click', function () {
                    var data = form.find(':input').serializeArray();
                    var url = '{{route('admin.administrator.assignmentRoles.save',[
                    'administrator'=>$adminToEdit
                    ])}}';
                    console.log(url,data);
                    if (data.length) {
                        $.post(url,data)
                    }
                });
            }
        })($)
    });
</script>
<script src="{{mixGafaFit('js/admin/react/admin/assignRol/build.js')}}"></script>
