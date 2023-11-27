import React from 'react'
import StoreItemList from "../StoreItemList";

export default class ItemDeleteForm extends React.Component {

    save() {
        let {
            product,
            id
        } = this.props;

        if (product) {
            let base_url = StoreItemList.get('product_delete_url');
            let url = base_url.replace('_|_', product.id);
            let data = $(this.refs[`${id}--delete-form`]).serializeArray();
            $.ajax({
                method: 'post',
                url: url,
                data: data,
                success: function (product) {
                    let products = StoreItemList.get('products');

                    products = products.filter(function (prod) {
                        return prod.id !== product.id;
                    });

                    let categories = StoreItemList.get('categories');

                    categories = categories.map(function (cat) {
                        if (cat.id === product.product_categories_id) {
                            cat.products_count = products.length;
                        }

                        return cat;
                    });

                    StoreItemList.set('products', products);
                    StoreItemList.set('categories', categories);
                },
                error: function (e) {
                    displayErrorsToast(e, StoreItemList.getLang('MessageErrorProductDelete'));
                }
            })
        }
    }

    render() {
        let product = this.props.product ? this.props.product : null;
        let {
            id,
        } = this.props;
        let csrf = StoreItemList.get('csrf');

        return (
            <div className={'item-form'}>
                <div className={'modal modal-fixed-footer category-modal'} id={id}>
                    <div className={'modal-content'}>
                        <form id={'item-delete-form'} ref={`${id}--delete-form`}>
                            <input name={'_token'} defaultValue={csrf} hidden={true}/>
                            <input hidden={true} defaultValue={product.id} name={'id'}/>
                            <h5>{StoreItemList.getLang('DeleteProduct')}</h5>
                        </form>
                    </div>
                    <div className={'modal-footer'}>
                        <a className="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                           href="#"> <i className="material-icons small">clear</i>
                            {StoreItemList.getLang('Cancel')}
                        </a>

                        <a className="modal-action modal-close waves-effect waves-green btn edit-button"
                           onClick={this.save.bind(this)}>
                            <i className="material-icons small">done</i>
                            {StoreItemList.getLang('Delete')}
                        </a>
                    </div>
                </div>
            </div>
        );
    }
}
