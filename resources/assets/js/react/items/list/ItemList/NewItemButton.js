import React from 'react'
import StoreItemList from "../StoreItemList";
import ItemForm from "./ItemForm";

export default class NewItemButton extends React.Component {
    constructor() {
        super();
    }

    componentDidMount() {
        InitModals($('.modal'));
    }

    render() {

        return (
            <div className={'new-item'}>
                <a className={'btn left'} href={'#new-item-modal'}>
                    <i className={'material-icons'}>add</i>
                    {StoreItemList.getLang('NewProduct')}
                </a>
                <ItemForm id={'new-item-modal'} title={StoreItemList.getLang('NewProduct')}/>
            </div>
        )
    }
}
