import { filterFields } from './defaultSearchKeys';

const processValue = (value = '') => value.trim().toLowerCase();
const matchValue = (query = '', value = '') => processValue(value).includes(processValue(query));
const matchField = (query = '', value = '') => {
	if (typeof value !== 'string') { return false; }
	return matchValue(query, value);
};
const checkStructureItem = (query = '', targetObj = {}) => filterFields.some(key => matchField(query, targetObj[key]));
const filterStructure = (query = '', structure = []) => structure.filter(item => checkStructureItem(query, item));

export default filterStructure;
