import React from 'react'
import CategoryList from "./CategoryList/CategoryList";
import ItemList from "./ItemList/ItemList";
import StoreItemList from "./StoreItemList";

export default class AppItemList extends React.Component {

    render() {
        return (
            <div className={'item-list'}>
                <CategoryList/>
                <ItemList/>
            </div>
        );
    }
}
