import React from 'react'
import CompanyRolView from "./CompanyRolView";
export default class TreeViewList extends React.Component {
    /**
     *
     * @returns {{hola: number}}
     */
    static get defaultProps() {
        return {
            companyTree: {},
            companyBlocked: null
        }
    }

    render() {
        let companyTree = this.props.companyTree;
        let companyBlocked = this.props.companyBlocked;
        let respuesta = [];

        for (let companyId in companyTree) {
            if (companyTree.hasOwnProperty(companyId)) {
                if (companyBlocked) {
                    //Asignacion solo a una company
                    if (parseInt(companyBlocked) !== parseInt(companyId)) {
                        //No coincide con la company que deseamos
                        continue;
                    }
                }
                let company = companyTree[companyId];
                respuesta.push(
                    <CompanyRolView company={company} key={`treeView-companyContainer-${companyId}`}/>
                )
            }
        }

        return respuesta
    }
}
