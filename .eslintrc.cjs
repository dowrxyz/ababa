module.exports = {
  root: true,
  env: {
    node: true,
  },

  extends: [
    "plugin:vue/vue3-recommended",
    // "eslint:recommended",
    "@vue/typescript/recommended",
    "plugin:prettier/recommended",
  ],

  parser: "vue-eslint-parser",

  parserOptions: {
    ecmaVersion: 2020,
    parser: "@typescript-eslint/parser",
  },

  rules: {
    "no-console": process.env.NODE_ENV === "production" ? "warn" : "off",
    "no-debugger": process.env.NODE_ENV === "production" ? "warn" : "off",
    "vue/no-deprecated-slot-attribute": "off",
    "@typescript-eslint/no-explicit-any": "off",
    "prettier/prettier": "error",
    "@typescript-eslint/explicit-function-return-type": "error",
    "@typescript-eslint/no-non-null-assertion": 0,
  },

  plugins: ["prettier"],
};
