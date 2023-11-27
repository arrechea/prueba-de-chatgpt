import React, {Component} from 'react';
import StoreReservation from "../../StoreReservation";
import Product from "./membership/membership";
import Slider from "react-slick";
import "./memberships.css";

class Memberships extends Component {
    constructor(props) {
        super(props);
        this.state = {
            memberships: StoreReservation.get("membershipSelection"),
            searchString: null
        };

        this.handleFilter = this.handleFilter.bind(this);
    }

    handleFilter(e) {
        let filterElements = this.state.memberships.filter(membership => {
            return membership.name.toLowerCase().includes(e.target.value.toLowerCase());
        });
        (e.target.value.length > 0) ? this.setState({
            searchString: e.target.value,
            memberships: filterElements
        }) : this.setState({searchString: null, memberships: StoreReservation.get("membershipSelection")});
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
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    }
                },
            ]
        };
        if (this.state.searchString != null) {
            settings.slidesToShow = this.state.memberships.length;
            settings.slidesToScroll = this.state.memberships.length;
        }

        let {memberships} = this.state;
        let {admin, handleCart} = this.props;

        let membershipList;

        if (admin === null) {
            membershipList = memberships.map(function (membership) {
                if (!membership.hide_in_front) {
                    return <Product key={membership.id} membershipData={membership} membershipsHandleCart={handleCart}/>
                }
            });
        } else {
            membershipList = memberships.map((function (membership, index) {
                return <Product key={membership.id} membershipData={membership} membershipsHandleCart={handleCart}/>
            }));
        }

        let {active} = this.props;
        return (
            <div id="memberships" className={`storeItemContent ${active ? 'active' : 'd-none'}`} key={'ReservationFancy--TabContent--memberships'}>
                <div className="product-search-container">
                    <form className="search-products" action="#">
                        <input type="text" onKeyUp={this.handleFilter} placeholder="Buscar membresías"/>
                    </form>
                    {/* <form className="filter-products" action="#">
                     <select name="filter-products">
                           <option value="0">Filtro</option>
                     </select>
                  </form> */}
                </div>
                <div className="products-container">
                    <Slider {...settings}>
                        {membershipList}
                    </Slider>
                </div>
            </div>
        )
    }
}

export default Memberships;
