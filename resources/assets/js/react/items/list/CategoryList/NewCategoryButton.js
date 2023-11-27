import React from 'react'
import StoreItemList from "../StoreItemList";
import CategoryForm from "./CategoryForm";

export default class NewCategoryButton extends React.Component {
    componentDidMount() {
        InitModals($('.modal'));
    }

    render() {

        return (
            <div className={'new-category'}>
                <a className={'btn left'} href={'#new-category-modal'}>
                    <i className={'material-icons'}>add</i>
                    {StoreItemList.getLang('NewCategory')}
                </a>
                <CategoryForm id={'new-category-modal'} title={StoreItemList.getLang('NewCategory')}/>
            </div>
        )
    }
}
