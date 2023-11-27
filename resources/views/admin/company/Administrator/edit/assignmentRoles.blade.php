<div class="model--border-radius">
    <h5 class="">{{__('users.AssignRoles')}} </h5>
    {{csrf_field()}}
    <div class="panelcombos col panelcombos_full">
        <div id="AdminAssignRol">
            <div id="AdminAssignRol--companies" style="display:none;">{{$companies}}</div>
            <div id="AdminAssignRol--rolesGafaFit" style="display:none;">[]</div>
            <div id="AdminAssignRol--adminToEdit" style="display:none;">{{$adminToEdit}}</div>
            <div id="AdminAssignRol--companyBlocked" style="display:none;">{{$company->id}}</div>
        </div>
    </div>
</div>

<script>
    window.RolesAsignement = {
        lang: {
            'addCompany': '{{__('users.AddCompany')}}',
            'addBrand': '{{__('users.AddBrand')}}',
            'addLocation': '{{__('users.AddLocation')}}',
            'companies': '{{__('users.Companies')}}',
            'brands': '{{__('users.Brands')}}',
            'locations': '{{__('users.Locations')}}',
            'roles': '{{__('users.Roles')}}',
        },
        urls: {
            getAdminRoles: '{{route('admin.company.administrator.assignmentRoles.getRoles',[
                    'company'=>$company,
                    'administrator'=>$adminToEdit,
                    'profile'=>$adminProfile
                    ])}}',
            getCompanyRoles: '{{route('admin.company.administrator.assignmentRoles.getCompanyRoles',[
                    'company'=>$company,
                    'companyToGet'=>'|',
                    ])}}',
            getCompanyRolesAndBrands: '{{route('admin.company.administrator.assignmentRoles.getCompanyRolesAndBrands',[
                    'company'=>$company,
                    'companyToGet'=>'|',
                    ])}}',
            getBrandRoles: '{{route('admin.company.administrator.assignmentRoles.getBrandRoles',[
                    'company'=>$company,
                    'brandToGet'=>'|',
                    ])}}',
            getLocationRoles: '{{route('admin.company.administrator.assignmentRoles.getLocationRoles',[
                    'company'=>$company,
                    'locationToGet'=>'|',
                    ])}}',
        },
        companies: JSON.parse($('#AdminAssignRol--companies').text()),
        rolesGafaFit: JSON.parse($('#AdminAssignRol--rolesGafaFit').text()),
        adminToEdit: JSON.parse($('#AdminAssignRol--adminToEdit').text()),
        companyBlocked: JSON.parse($('#AdminAssignRol--companyBlocked').text())
    };
    $(document).ready(function () {
        (function ($) {
            var form = $('#AdminAssignRol').closest('.User--assignmentRoles');
            var yaAplicado = window.RolesAsignementAplicado;
            window.RolesAsignementAplicado = true;

            if (!yaAplicado) {
                form.find('.modal-close').on('click', function () {
                    var data = form.find(':input').serializeArray();
                    var url = '{{route('admin.company.administrator.assignmentRoles.save',[
                    'company'=>$company,
                    'administrator'=>$adminToEdit,
                    'profile'=>$adminProfile
                    ])}}';
                    console.log(url, data);
                    if (data.length) {
                        $.post(url, data)
                    }
                });
            }
        })($)
    });
</script>
<script src="{{mixGafaFit('js/admin/react/admin/assignRol/build.js')}}"></script>
