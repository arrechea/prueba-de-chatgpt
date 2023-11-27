import React, {Component} from 'react';
import StoreReservation from "../../StoreReservation";
import Slider from "react-slick";
import Product from './product/product';

class Products extends Component {
    constructor(props) {
        super(props);
        this.state = {
            products: StoreReservation.get("products"),
            searchString: null
        };

        this.handleFilter = this.handleFilter.bind(this);
    }

    handleFilter(e) {
        let filterElements = this.state.products.filter(product => {
            return product.name.toLowerCase().includes(e.target.value.toLowerCase());
        });
        (e.target.value.length > 0) ? this.setState({
            searchString: e.target.value,
            products: filterElements
        }) : this.setState({searchString: null, products: StoreReservation.get("product")});
    }

    render() {
        var settings = {
            dots: true,
            infinite: false,
            speed: 500,
            slidesToShow: 3,
            slidesToScroll: 3,
            rows: 1,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    },
                },
            ]
        };

        if (this.state.searchString != null) {
            settings.slidesToShow = this.state.products.length;
            settings.slidesToScroll = this.state.products.length;
        }

        let {active} = this.props;
        return (
            <div id="products" className={`storeItemContent ${active ? 'active' : 'd-none'}`} key={'ReservationFancy--TabContent--products'}>
                <div className="product-search-container">
                    <form className="search-products" action="#">
                        <input type="text" onKeyUp={this.handleFilter} placeholder="Buscar productos"/>
                    </form>
                    {/* <form className="filter-products" action="#">
                        <select name="filter-products">
                            <option value="0">Filtro</option>
                        </select>
                    </form> */}
                </div>
                <div className="products-container">
                    <Slider {...settings}>
                        {
                            this.state.products.map((product, index) => {
                                return <Product key={product.id} productData={product}
                                                productsHandleCart={this.props.handleCart}/>
                            })
                        }
                    </Slider>
                </div>
            </div>
        )
    }
}

export default Products;
