# Pharmacy Management System

## Overview

The Pharmacy Management System is designed to streamline the operations of a pharmacy by managing customers, pharmacists, medicines, suppliers, branches, and managers. This system ensures efficient handling of sales transactions, inventory management, and branch administration.

## Entity-Relationship Diagram (ERD)

![ERD](<Diagrams/ER Diagram/Entity Relationtionship Diagram.jpg>)

## Entities and Attributes

### Customer
- **ID:** Unique identifier for the customer.
- **Name:** Full name of the customer.
- **Phone number:** Contact number of the customer.

### Pharmacist
- **Username:** Unique username for the pharmacist.
- **Password:** Password for the pharmacist's account.
- **Name:** Full name of the pharmacist.
- **Email:** Email address of the pharmacist.

### Medicine
- **ID:** Unique identifier for the medicine.
- **Medicine name:** Name of the medicine.
- **Price:** Price of the medicine.
- **Type:** Type/category of the medicine.
- **Quantity:** Available quantity of the medicine.
- **Expiry date:** Expiry date of the medicine.

### Suppliers
- **ID:** Unique identifier for the supplier.
- **Name:** Name of the supplier.


### Manager
- **Username:** Unique username for the manager.
- **Password:** Password for the manager's account.
- **Name:** Full name of the manager.
- **Email:** Email address of the manager.

## Relationships

### Customer and Pharmacist
- **Sales:** Represents the transaction between a customer and a pharmacist. Attributes include:
  - **Time:** Time of the transaction.
  - **Quantity:** Quantity of medicines purchased.
  - **Total:** Total amount of the transaction.
- **Cardinality:** Many customers can buy from one pharmacist (N:1).

### Pharmacist and Branch
- **Works At:** Represents the employment relationship between a pharmacist and a branch.
- **Cardinality:** Many pharmacists can work at one branch (N:1).

### Medicine and Suppliers
- **Supplies:** Represents the supply relationship between a supplier and a medicine.
- **Cardinality:** Many medicines can be supplied by one supplier (N:1).

### Medicine and Branch
- **Contains:** Represents the inventory of medicines in a branch.
- **Cardinality:** Many medicines can be contained in one branch (N:1).

### Branch and Manager
- **Manages:** Represents the management relationship between a manager and a branch.
- **Cardinality:** One manager can manage many branches (1:N).

## Summary

This pharmacy management system efficiently organizes and manages the core components of a pharmacy, including customer transactions, pharmacist details, medicine inventory, supplier information, branch data, and managerial oversight. The ERD illustrates the relationships between these entities, ensuring a comprehensive understanding of the system's structure and functionality.