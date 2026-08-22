/** @type {import("prettier").Config} */
const config = {
    attributeGroups: [
        '^data-namespace-typo3-fluid$',
        '^xmlns:',
        '$DEFAULT'
    ],
    attributeSort: 'ASC',
    bracketSameLine: true,
    htmlWhitespaceSensitivity: 'ignore',
    overrides: [
        {
            files: ['*.xml', '*.xlf'],
            options: {
                parser: 'xml',
                xmlSortAttributesByKey: true
            }
        }
    ],
    plugins: ['prettier-plugin-organize-attributes', '@prettier/plugin-xml'],
    singleAttributePerLine: true,
    tabWidth: 4,
};

export default config;
