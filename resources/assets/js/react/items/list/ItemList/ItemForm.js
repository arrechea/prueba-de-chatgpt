import React from 'react'
import StoreItemList from "../StoreItemList";

export default class ItemForm extends React.Component {
    constructor(props) {
        super();
        this.state = {
            categories: StoreItemList.get('categories'),
            margin: props.product ? (props.product.price - props.product.unit_cost) : 0,
        };

        // StoreItemList.addSegmentedListener(['categories'], this.updateCategories.bind(this));

        this.get = this.get.bind(this);
    }

    save() {
        let {
            product,
            id
        } = this.props;

        let url = null;


        if (product) {
            let base_url = StoreItemList.get('product_url');
            url = base_url.replace('_|_', product.id);
        } else {
            url = StoreItemList.get('product_new_url');
        }

        if (url) {
            let selected_category = StoreItemList.get('selected_category');
            if (selected_category) {
                let data = $(this.refs[`${id}--edit-form`]).serializeArray();
                data.push({
                    name: 'product_categories_id',
                    value: selected_category.id
                });
                $.ajax({
                    method: 'post',
                    url: url,
                    data: data,
                    success: function (product) {
                        let products = StoreItemList.get('products');

                        if (products.find(function (cat) {
                            return cat.id === product.id;
                        })) {
                            products = products.map(function (prod) {
                                if (prod.id === product.id) {
                                    prod = product;
                                }

                                return prod;
                            });
                        } else {
                            products.push(product);
                        }

                        let categories = StoreItemList.get('categories');

                        categories = categories.map(function (cat) {
                            if (cat.id === parseInt(product.product_categories_id)) {
                                cat.products_count = products.length;
                            }

                            return cat;
                        });

                        StoreItemList.set('products', products);
                        StoreItemList.set('categories', categories);
                    },
                    error: function (e) {
                        displayErrorsToast(e, StoreItemList.getLang('MessageErrorProductSave'));
                    }
                });
            }
        }
    }

    get(attribute) {
        let product = this.props.product ? this.props.product : null;
        return product ? (product[attribute] ? product[attribute] : '') : '';
    }

    changeMargin() {
        let price = this.refs.price.value;
        let cost = this.refs.unit_cost.value;

        this.setState({
            margin: price - cost
        });
    }

    render() {
        let product = this.props.product ? this.props.product : null;

        let {
            id,
            title,
        } = this.props;
        let csrf = StoreItemList.get('csrf');

        let component = this;

        let inputId = function () {
            if (product) {
                return (
                    <input name={'id'} hidden={true} defaultValue={component.get('id')}/>
                );
            } else {
                return null;
            }
        };

        let categories = this.state.categories;

        let tax = this.get('sales_tax');

        tax = tax !== '' ? tax : 16;

        return (
            <div className={'item-form'}>
                <div className={'modal modal-fixed-footer item-modal'} id={id}>
                    <div className={'modal-content'}>
                        <form id={'item-edit-form'} ref={`${id}--edit-form`}>
                            <input name={'_token'} defaultValue={csrf} hidden={true}/>
                            <h5>{title}</h5>
                            {inputId()}
                            <div className={'input-field col s6'}>
                                <input type={'text'} id={'item-name'} name={'name'}
                                       defaultValue={this.get('name')}/>
                                <label htmlFor={'item-name'}
                                       className={'active'}>{StoreItemList.getLang('Name')}</label>
                            </div>

                            {/*<div className={'col s6 input-field'}>*/}
                            {/*<select className={''} name={'product_categories_id'} id={'select-categories_id'}*/}
                            {/*defaultValue={this.get('product_categories_id')}>*/}
                            {/*{categories.map(function (category) {*/}
                            {/*return (*/}
                            {/*<option key={`${id}--category-option-${category.id}`}*/}
                            {/*value={category.id}>{category.name}</option>*/}
                            {/*)*/}
                            {/*})}*/}
                            {/*</select>*/}
                            {/*<label htmlFor={'select-categories_id'}>{StoreItemList.getLang('Category')}</label>*/}
                            {/*</div>*/}

                            <div className={'col s12 input-field'}>
                                <textarea name={'description'} id={'description-textarea'}
                                          defaultValue={this.get('description')}></textarea>
                                <label htmlFor={'description-textarea'}
                                       className={'active'}>{StoreItemList.getLang('Description')}</label>
                            </div>

                            <div className={'col s12 m4 input-field'}>
                                <input type={'number'} name={'unit_cost'} id={'input-unit_cost'} ref={'unit_cost'}
                                       defaultValue={this.get('unit_cost')} onChange={this.changeMargin.bind(this)}/>
                                <label htmlFor={'input-unit_cost'}
                                       className={'active'}>{StoreItemList.getLang('UnitCost')}</label>
                            </div>

                            <div className={'col s12 m4 input-field'}>
                                <input type={'number'} name={'price'} id={'input-price'} ref={'price'}
                                       defaultValue={this.get('price')} onChange={this.changeMargin.bind(this)}/>
                                <label htmlFor={'input-price'}
                                       className={'active'}>{StoreItemList.getLang('Price')}</label>
                            </div>

                            <div className={'col s12 m4 input-field'}>
                                <input type={'number'} name={'sales_tax'} id={'input-sales_tax'}
                                       defaultValue={tax}/>
                                <label htmlFor={'input-sales_tax'}
                                       className={'active'}>{StoreItemList.getLang('SalesTax')}</label>
                            </div>

                            <div className={'col s12 m8 input-field'}>
                                <input type={'text'} name={'provider'} id={'input-provider'}
                                       defaultValue={this.get('provider')}/>
                                <label htmlFor={'input-provider'}
                                       className={'active'}>{StoreItemList.getLang('Provider')}</label>
                            </div>

                            <div className={'col s12 m4 input-field'}>
                                <input type={'text'} name={'margin'} id={'margin'} readOnly={true}
                                       value={this.state.margin}/>
                                <label htmlFor={'margin'}
                                       className={'active'}>{StoreItemList.getLang('Margin')}</label>
                            </div>
                        </form>
                    </div>
                    <div className={'modal-footer'}>
                        <a className="modal-close btn edit-button save-button-footer"
                           href="#"> <i className="material-icons small">clear</i>
                            {StoreItemList.getLang('Cancel')}
                        </a>

                        <a className="modal-action modal-close btn edit-button"
                           onClick={this.save.bind(this)}>
                            <i className="material-icons small">done</i>
                            {StoreItemList.getLang('Save')}
                        </a>
                    </div>
                </div>
            </div>
        );
    }
}
