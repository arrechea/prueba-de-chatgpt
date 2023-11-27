import React from 'react'
import StorageSpecialTextForm from './StorageSpecialTextForm';
import Group from './groups/Group';

export default class AppSpecialTextsForm extends React.Component {
    constructor() {
        super();
        this.state = {
            loading: true,
            structure: [],
            values: [],
        };

        this.PrintGroups = this.PrintGroups.bind(this);
        this.PrintGroups = this.PrintGroups.bind(this);
        this.addGroup = this.addGroup.bind(this);
        this.removeGroup = this.removeGroup.bind(this);
        this.getGroupIndices = this.getGroupIndices.bind(this);
        this.addField = this.addField.bind(this);
        this.getFieldIndices = this.getFieldIndices.bind(this);
        this.getFieldValue = this.getFieldValue.bind(this);
        this.removeField = this.removeField.bind(this);
    }

    componentDidMount() {
        let component = this;
        $.ajax({
            method: 'get',
            url: StorageSpecialTextForm.fields_url,
            dataType: 'json',
            success: function (data) {
                StorageSpecialTextForm.setLoading('structure', false);
                let val_load = StorageSpecialTextForm.loading.values;
                component.setState({
                    structure: data,
                    loading: val_load
                });
            }
        });
        if (StorageSpecialTextForm.values_url) {
            $.ajax({
                method: 'get',
                url: StorageSpecialTextForm.values_url,
                dataType: 'json',
                success: function (data) {
                    StorageSpecialTextForm.setLoading('values', false);
                    let struc_load = StorageSpecialTextForm.loading.structure;
                    component.setState({
                        values: data,
                        loading: struc_load
                    });
                }
            });
        } else {
            StorageSpecialTextForm.setLoading('values', false);
        }
    }

    componentDidUpdate() {
        $('select').material_select();
        inputsAsterisk();
    }

    PrintGroups() {
        let component = this;
        return component.state.structure.map(function (group) {
            return (
                <Group
                    key={`group-wrapper--${group.id}`}
                    group={group}
                    getGroupIndices={component.getGroupIndices}
                    values={component.state.values}
                    addField={component.addField}
                    getFieldIndices={component.getFieldIndices}
                    getFieldValue={component.getFieldValue}
                    removeField={component.removeField}
                    addGroup={component.addGroup}
                    removeGroup={component.removeGroup}
                />
            );
        });
    }

    addGroup(group) {
        if (group.can_repeat) {
            let values = this.state.values;
            if (!values.find(function (item) {
                return item.catalogs_groups_id === group.id;
            })) {
                this.insertEmptyGroup(group, -1);
            }
            let max_index = Math.max.apply(Math, this.getGroupIndices(group));
            this.insertEmptyGroup(group, max_index + 1);
        }
    }

    getGroupIndices(group) {
        let indices = [];
        let component = this;
        if (group.can_repeat) {
            let group_values = component.state.values.filter(function (item) {
                return item.catalogs_groups_id === group.id;
            }).map(function (item) {
                return item.catalogs_groups_index;
            });
            $.each(group_values, function (i, e) {
                if ($.inArray(e, indices) === -1) indices.push(e);
            });
        }

        if (!indices.length) {
            indices.push(0);
        }

        return indices.sort();
    }

    removeGroup(group, group_index) {
        let indices = this.getGroupIndices(group);
        if (indices.length > 0) {
            let values = this.state.values;
            let new_values = values.filter(function (item) {
                return !(item.catalogs_groups_id === group.id && item.catalogs_groups_index === group_index);
            });
            this.setState({
                values: new_values
            });
        }
    }

    addField(field, value_group_index) {
        if (field.can_repeat) {
            let values = this.state.values;
            if (!values.find(function (item) {
                return item.catalogs_fields_id === field.id &&
                    item.catalogs_groups_id === field.catalogs_groups_id &&
                    item.catalogs_groups_index === value_group_index
            })) {
                this.insertEmptyField(field, value_group_index, -1);
            }

            let indices = this.getFieldIndices(field, value_group_index);
            let max_index = Math.max.apply(Math, indices);
            this.insertEmptyField(field, value_group_index, max_index);
        }
    }

    insertEmptyField(field, value_group_index, max_index) {
        let values = this.state.values;
        let value = Object.assign({}, StorageSpecialTextForm.default_value);
        value.catalogs_groups_id = field.catalogs_groups_id;
        value.catalogs_fields_id = field.id;
        value.catalogs_groups_index = value_group_index;
        value.catalogs_fields_index = max_index + 1;
        values.push(value);
        this.setState({
            values: values
        });
    }

    insertEmptyGroup(group, max_index) {
        let field = Object.assign({}, StorageSpecialTextForm.default_value);
        field.catalogs_groups_id = group.id;
        this.insertEmptyField(field, max_index, -1)
    }

    removeField(field, value_group_index, value_field_index) {
        let indices = this.getFieldIndices(field, value_group_index);
        if (indices.length > 1) {
            let values = this.state.values;
            let new_values = values.filter(function (item) {
                return !(
                    item.catalogs_groups_id === field.catalogs_groups_id &&
                    item.catalogs_groups_index === value_group_index &&
                    item.catalogs_fields_id === field.id &&
                    item.catalogs_fields_index === value_field_index
                );
            });
            this.setState({
                values: new_values
            });
        }
    }

    getFieldIndices(field, group_index) {
        let indices = [];
        let component = this;
        if (field.can_repeat) {
            let values = component.state.values;
            let filtered_values = values.filter(function (item) {
                return item.catalogs_groups_id === field.catalogs_groups_id &&
                    item.catalogs_groups_index === group_index &&
                    item.catalogs_fields_id === field.id;
            });
            let field_values = filtered_values.map(function (item) {
                return item.catalogs_fields_index;
            });
            $.each(field_values, function (i, e) {
                if ($.inArray(e, indices) === -1) indices.push(e);
            });
        }

        if (!indices.length) {
            indices.push(0);
        }

        return indices.sort();
    }

    getFieldValue(field, group_index, field_index, def = '') {
        let value = Object.assign({}, this.state.values.find(function (item) {
            return item.catalogs_fields_id === field.id &&
                item.catalogs_groups_id === field.catalogs_groups_id &&
                item.catalogs_groups_index === group_index &&
                item.catalogs_fields_index === field_index;
        }));

        return typeof value === 'object' && value.hasOwnProperty('value') && value.value !== null && value.value !== '' ? value.value : def;
    }

    render() {
        if (this.state.loading) {
            return (
                <div className="progress" style={{backgroundColor: 'var(--light-color)'}}>
                    <div className="indeterminate" style={{backgroundColor: 'var(--main-color)'}}/>
                </div>
            );
        } else {
            return (
                <div>
                    {this.PrintGroups()}
                </div>
            );
        }
    }
}
