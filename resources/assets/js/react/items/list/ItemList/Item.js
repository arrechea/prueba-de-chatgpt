import React from 'react'
import StoreItemList from "../StoreItemList";
import ItemDeleteForm from "./ItemDeleteForm";
import ItemForm from "./ItemForm";

export default class Item extends React.Component {
    constructor(props) {
        super();

        this.deleteButton = this.deleteButton.bind(this);
    }

    componentDidMount() {
        let product = this.props.product;

        $(`#product-edit-modal-${product.id}`).modal();
        $(`#item-delete-modal-${product.id}`).modal();
    }

    deleteButton() {
        let product = this.props.product;

        return (
            <div>
                <a className={'btn btn-small col s6 item-list-edit-button'}
                   href={`#item-delete-modal-${product.id}`}>
                    <i className={'material-icons'}>cancel</i>
                </a>
                <ItemDeleteForm id={`item-delete-modal-${product.id}`} product={product}/>
            </div>
        )
    }

    render() {
        let {
            product,
        } = this.props;

        return (
            <div
                className={'product-list-item'}>
                <span hidden={true}>{product.name}</span>
                <div className={'row'}>
                    <div className={'product-list-name col s8'}>
                        {product.name}
                    </div>

                    <div className={'col s4 product-edit-buttons'}>
                        <a className={'btn btn-small col s6 item-list-edit-button'}
                           href={`#product-edit-modal-${product.id}`}>
                            <i className={'material-icons'}>edit</i>
                        </a>
                        {this.deleteButton()}
                    </div>
                    <ItemForm id={`product-edit-modal-${product.id}`} title={StoreItemList.getLang('EditProduct')}
                              product={product}/>
                </div>
            </div>
        )
    }
}
