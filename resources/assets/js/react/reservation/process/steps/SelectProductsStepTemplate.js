import React from 'react'
import Combos from "./combos/combos";
import Memberships from "./memberships/memberships";
import Products from './products/products';
import CheckOut from "./checkout/checkout";
import Touchpoint from './checkout/touchpoint';
import StoreReservation from "../StoreReservation";

export default class SelectProductsStepTemplate extends React.Component {
    /**
     *
     * @returns {XML}
     */
    constructor(props) {
        super(props);

        this.state = {
            cart: [],
            admin: StoreReservation.get('admin'),
            location: StoreReservation.get('location'),
        };

        this.handleNavSelector = this.handleNavSelector.bind(this);
        this.handleCart = this.handleCart.bind(this);
        this.handleCartDelete = this.handleCartDelete.bind(this);
    }

    componentDidMount() {
        this.setState({
            cart: (this.props.cartItems) ? this.props.cartItems : [],
        });
    }

    handleCart(product) {
        let cart = this.state.cart;
        let existInCart = null;
        existInCart = cart.filter(item => (item.id === product.id && item.type === product.type));
        if (existInCart.length > 0) {
            cart = cart.map(item => {
                if (item.id === existInCart[0].id) {
                    item.amount += product.amount;
                }
                // if (product.reservations_max !== null && item.amount > product.reservations_max + product.credits) {
                    if (product.reservations_max !== null && item.amount > product.reservations_max) {
                    // item.amount = product.reservations_max + product.credits;
                    item.amount = product.reservations_max;
                }
                return item;
            })
        } else {
            // if (product.reservations_max !== null && product.amount > product.reservations_max + product.credits) {
                if (product.reservations_max !== null && product.amount > product.reservations_max) {
                // product.amount = product.reservations_max + product.credits;
                product.amount = product.reservations_max;
            }
            cart.push(product);
        }
        this.setState({cart: cart});
    }

    handleCartDelete(productId) {
        let elements = this.state.cart.filter(item => item.id != productId);
        this.setState({
            cart: elements
        });
    }

    handleNavSelector(e) {
        e.preventDefault();
        let selected = document.querySelector('.main-content .SelectProductsStepNav ul li.active').classList.remove('active');
        e.target.parentNode.parentNode.classList.add('active');
        document.querySelector('.storeItemContent.active').classList.add('d-none');
        document.querySelector('.storeItemContent.active').classList.remove('active');
        document.querySelector(e.target.parentNode.getAttribute('id')).classList.remove('d-none');
        document.querySelector(e.target.parentNode.getAttribute('id')).classList.add('active');
    }

    printTab(tab, active = false) {
        let lang = StoreReservation.get('lang');
        let title = lang[`${tab}.tab_title`];
        let show = true;
        switch (tab) {
            case 'products':
                let products = StoreReservation.get("products");
                show = !!products.length;
                break;
            case 'memberships':
                let memberships = StoreReservation.get("membershipSelection");
                show = !!memberships.length;
                break;
            case 'combos':
                let combos = StoreReservation.get("combosSelection");
                show = !!combos.length;
                break;
        }
        if (show) {
            return (<li className={active ? 'active' : ''}>
                <div id={`#${tab}`} key={`ReservationFancy--tab_link--${tab}`}>
                    <span onClick={this.handleNavSelector}>{title}</span>
                </div>
            </li>);
        }
    }

    getActivesObject() {
        const {admin} = this.state;
        let default_store_tab = StoreReservation.get('default_store_tab');

        let actives = [];
        if (admin) {
            actives.push({id: 'products', active: false});
        }
        actives.push({id: 'combos', active: false});
        actives.push({id: 'memberships', active: false});
        let done = false;

        if (default_store_tab !== null) {
            let active_tab = actives.findIndex(function (item) {
                return item.id === default_store_tab;
            });
            if (active_tab >= 0) {
                let active_tab_content = actives[active_tab];
                active_tab_content.active = true;
                done = true;
            }
        }

        if (admin && !done) {
            let products = StoreReservation.get("products");
            actives[0].active = products.length > 0;
            done = products.length > 0;
        }
        if (!done) {
            let index = admin ? 1 : 0;
            let combos = StoreReservation.get("combosSelection");
            actives[index].active = combos.length > 0;
            done = combos.length > 0;
        }
        if (!done) {
            let index = admin ? 2 : 1;
            let memberships = StoreReservation.get("membershipSelection");
            actives[index].active = memberships.length > 0;
            done = memberships.length > 0;
        }

        return actives;
    }

    printTabs() {
        let actives = this.getActivesObject();
        let tabs = [];
        let component = this;
        actives.forEach(function (active) {
            tabs.push(component.printTab(active.id, active.active));
        });

        return (tabs);
    }

    printContentTab(tab, active = false) {
        let return_value = null;
        const {admin} = this.state;
        switch (tab) {
            case 'products':
                return_value = (admin != null
                    ? <Products handleCart={this.handleCart} active={active}/>
                    : null);
                break;
            case 'memberships':
                return_value = (StoreReservation.hasMoreThanOneReservation() ? null :
                    <Memberships admin={admin} handleCart={this.handleCart} active={active}/>);
                break;
            case 'combos':
                return_value = (<Combos admin={admin} handleCart={this.handleCart} active={active}/>);
                break;
        }

        return return_value;
    }

    printContent() {
        let actives = this.getActivesObject();
        let tabs = [];
        let component = this;
        actives.forEach(function (active) {
            tabs.push(component.printContentTab(active.id, active.active));
        });

        return (tabs);
    }

    render() {
        const {location, admin} = this.state;
        let CompanyName = location.company.name.toUpperCase();

        return (
            <div className="custom-container main-content productSelection">
                <div className="GfStore__ProductsCatalog productSelection__content">
                    <nav className="SelectProductsStepNav">
                        <ul>
                            {
                                this.printTabs()
                            }
                        </ul>
                    </nav>
                    {this.printContent()}
                </div>
                <CheckOut cart={this.state.cart} handleCartDelete={this.handleCartDelete}/>
                <Touchpoint cart={this.state.cart}/>
            </div>
        )
    }
}
