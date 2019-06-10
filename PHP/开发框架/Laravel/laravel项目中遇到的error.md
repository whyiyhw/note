# 问题点

---

## 问题描述1

- `this-action-is-unauthorized`
- 这是因为 `request` 层 的 `authorize` `return` 为 `false` 导致的

## 问题解决方案1

- 修改为 `return true` 就解决了

## 问题描述2

- 使用 `Model softDelete` 特性后 `validate` 的 `unique` 无法发挥作用

## 问题解决方案2

- 设计者对于这个的解释为 唯一键与软删除 本来就不能共存
