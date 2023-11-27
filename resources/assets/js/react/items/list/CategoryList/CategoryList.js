import React from 'react'
import StoreItemList from "../StoreItemList";
import Category from "./Category";
import NewCategoryButton from "./NewCategoryButton";

export default class CategoryList extends React.Component {
    constructor() {
        super();
        this.state = {
            categories: StoreItemList.get('categories'),
            selected_category: null,
        };

        StoreItemList.addSegmentedListener(['categories', 'selected_category'], this.updateCategories.bind(this));
    }

    updateCategories() {
        this.setState({
            categories: StoreItemList.get('categories'),
            selected_category: StoreItemList.get('selected_category'),
        });

        InitModals($('.modal'));
    }

    render() {
        let {
            categories,
            selected_category,
        } = this.state;

        return (
            <div className={'category-list col s12 m6'}>
                <div className={'col s12'}>
                    <NewCategoryButton/>
                </div>
                <div className={'col s12 categories-list'}>
                    {categories.map(function (category, index) {
                        return (
                            <Category category={category} key={`item-list-category--${category.id}`}
                                      selected_category={selected_category} index={index}/>
                        )
                    })}
                </div>
            </div>
        )
    }
}
