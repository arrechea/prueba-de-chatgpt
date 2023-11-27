import React from 'react'
import CategoryForm from "./CategoryForm";
import StoreItemList from "../StoreItemList";
import CategoryDeleteForm from "./CategoryDeleteForm";

export default class Category extends React.Component {
    constructor(props) {
        super();

        this.deleteButton = this.deleteButton.bind(this);
        this.canDelete = this.canDelete.bind(this);
    }

    select() {
        let category = this.props.category;
        let selected_category = StoreItemList.get('selected_category');
        if (!selected_category || (category.id !== selected_category.id)) {
            StoreItemList.set('selected_category', category);
        }
    }

    canDelete() {
        let category = this.props.category;

        return category.products_count === 0;
    }

    deleteButton() {
        let category = this.props.category;

        if (this.canDelete()) {
            return (
                <div>
                    <a className={'btn btn-small col s6 item-list-edit-button'}
                       href={`#category-delete-modal-${category.id}`}>
                        <i className={'material-icons'}>cancel</i>
                    </a>
                    <CategoryDeleteForm id={`category-delete-modal-${category.id}`} category={category}/>
                </div>
            )
        } else {
            return null;
        }
    }

    render() {
        let {
            category,
            selected_category,
            index
        } = this.props;

        let selected = (selected_category && category.id === selected_category.id) || (selected_category === null && index === 0);

        return (
            <div
                className={'category-list-item ' + `${selected ? 'category-selected' : ''}`}
                onClick={this.select.bind(this)}>
                <div className={'row'}>
                    <div className={'category-list-name col s8'}>
                        {category.name}
                    </div>
                    <div className={'col s4 category-edit-buttons'}>
                        <a className={'btn btn-small col s6 item-list-edit-button'}
                           href={`#category-edit-modal-${category.id}`}>
                            <i className={'material-icons'}>edit</i>
                        </a>
                        {this.deleteButton()}
                    </div>
                </div>

                <CategoryForm id={`category-edit-modal-${category.id}`} title={StoreItemList.getLang('EditCategory')}
                              category={category}/>

            </div>
        )
    }
}
