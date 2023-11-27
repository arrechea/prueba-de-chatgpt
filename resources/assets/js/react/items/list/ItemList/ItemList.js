import React from 'react'
import StoreItemList from "../StoreItemList";
import NewItemButton from "./NewItemButton";
import Item from "./Item";
import Pagination from "react-js-pagination";

export default class ItemList extends React.Component {
    constructor() {
        super();
        let categories = StoreItemList.get('categories');
        this.state = {
            products: StoreItemList.get('products'),
            page_products: [],
            activePage: 1,
            loading: false,
            show_new_button: categories ? categories.length > 0 : false
        };

        StoreItemList.addSegmentedListener(['products'], this.updateProducts.bind(this));
        StoreItemList.addSegmentedListener(['selected_category'], this.updateSelectedCategory.bind(this));
        StoreItemList.addSegmentedListener(['categories'], this.updateCategories.bind(this));
        this.getProducts = this.getProducts.bind(this);
    }

    componentDidMount() {
        this.getProducts();
    }

    updateCategories() {
        let categories = StoreItemList.get('categories');
        this.setState({
            show_new_button: categories ? categories.length > 0 : false
        });
    }

    getProducts() {
        let url = StoreItemList.get('product_list_url');
        let selected_category = StoreItemList.get('selected_category');
        let component = this;

        if (selected_category) {
            let data = {
                'category': selected_category.id
            };

            component.setState({
                loading: true
            });

            $.ajax({
                url: url,
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                type: 'get',
                data: data,
                success: function (json) {
                    let products = json.products ? json.products : [];
                    let category = json.category;

                    if (category) {
                        let categories = StoreItemList.get('categories');
                        categories = categories.map(function (cat) {
                            if (cat.id === category.id) {
                                cat = category;
                            }

                            return cat;
                        });

                        StoreItemList.set('categories', categories);
                    }

                    StoreItemList.set('products', products);

                    component.setState({
                        loading: false
                    })
                }
            });
        }
    }

    updateSelectedCategory() {
        this.getProducts();
    }

    updateProducts() {
        this.setState({
            products: StoreItemList.get('products'),
        });

        this.handlePageChange(this.state.activePage ? this.state.activePage : 1);
    }

    handlePageChange(page) {
        let number = 15;
        let products = StoreItemList.get('products');
        let max = Math.floor((products.length - 1) / number) + 1;
        if (max < page) {
            page = max;
        }

        let init = (page - 1) * number;
        let last = init + (number - 1);

        let filtered = products.filter(function (product, index) {
            return index >= init && index <= last;
        });

        this.setState({
            activePage: page,
            page_products: filtered
        });
    }

    showNewButton() {

    }

    render() {
        let {
            page_products,
            products,
            activePage,
        } = this.state;

        let total = products.length;

        if (this.state.loading) {
            return (
                <div>
                    {StoreItemList.getLang('Loading')}
                </div>
            );
        } else {
            return (
                <div className={'item-list col s12 m6'}>
                    <div className={'col s12'}>
                        {this.state.show_new_button ? (<NewItemButton/>) : null}
                    </div>
                    <div className={'col s12 product-list'} ref={'item_list'}>
                        {page_products.map(function (product) {
                            return (
                                <Item product={product} key={`item-list-product--${product.id}`}/>
                            )
                        })}
                    </div>

                    <Pagination
                        activePage={activePage}
                        itemsCountPerPage={15}
                        totalItemsCount={total}
                        onChange={this.handlePageChange.bind(this)}
                    />
                </div>
            )
        }
    }
}
