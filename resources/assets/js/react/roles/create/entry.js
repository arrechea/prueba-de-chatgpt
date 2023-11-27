import React from 'react'
import ReactDOM from 'react-dom'
import AppRolesCreate from "./AppRolesCreate";

let company_level = null;
try {
    let company = $('#EditRoles--companyEditable').text();
    company_level = company !== '';
} catch (e) {
    console.error('user:\n', e)
}

ReactDOM.render(<AppRolesCreate company_level={company_level}/>, document.getElementById('RoleCreate'));
