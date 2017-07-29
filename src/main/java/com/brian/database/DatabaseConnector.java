package com.brian.database;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;

/**
 * Created by Brian on 6/17/2017.
 */
public class DatabaseConnector {
    private static Connection connection;
    
    
    public DatabaseConnector(final Connection connectionInput){
        connection = connectionInput;
    }
    
    public DatabaseConnector(final String host, final String port, final String username, final String password, final String schemaName, final String type){
        
    }

    /**
     * Prints out the current schema associated with the database
     * Schema = all of the tables and the definitions of the tables
     *
     * */
    public static void Schema(Connection connection){
        try {
            Statement stmt, stmt2;
            ResultSet rs, columns;
            stmt = connection.createStatement();
            stmt2 = connection.createStatement();

            System.out.println("Shema of com.brian.Inventory database\n");
            stmt.execute("SHOW TABLES from inventory");
            rs = stmt.getResultSet();

            while(rs.next()){
                String query = "SHOW COLUMNS FROM " + rs.getString("Tables_in_inventory");
                stmt2.execute(query);
                columns = stmt2.getResultSet();
                System.out.println("\t" + rs.getString("Tables_in_inventory"));
                while(columns.next()){
                    //TODO figure out column names dynamically
                    System.out.println("Field: " + columns.getString("Field") + "\t\tType: " + columns.getString("Type"));
                }
                System.out.println("\n");
            }
        }
        catch (SQLException ex){
            // handle any errors
            System.out.println("SQLException: " + ex.getMessage());
            System.out.println("SQLState: " + ex.getSQLState());
            System.out.println("VendorError: " + ex.getErrorCode());
        }
    }

    /**
     * Inserts into the database, into the specified table, the data in the input string array
     * Because this statement can affect several columns, an array needs to be passed to the method
     * Args: Table (table to be inserted into), data (data to be inserted into the specified table)
     * */
    public static void Insert(String table, ArrayList<String> data){
        Statement stmt;
        ResultSet rs = null;
        ResultSet columns = null;
        try {
            stmt = connection.createStatement();
            if (stmt.execute("")) {
                rs = stmt.getResultSet();
            }
            while(rs.next()){
                System.out.println("");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    /**
     * Updates columns in the database that hold information but need to be changed to reflect current state
     * This will only affect one column of one table
     * Args: table (table to be inserted into), column (column we are updating), data (data we are putting into the column)
     *          matcherColumn (column we are going to search for), matcherData (data we are going to find in the column)
     *
     * */
    public static void Update(String table, String column, String data, String matcherColumn, String matcherData){
        Statement stmt;
        try {
            stmt = connection.createStatement();
            stmt.execute("UPDATE " + table + " SET " + column +"=" + data + " WHERE " + matcherColumn + " LIKE '%" + matcherData + "%'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    /**
     * Deletes the row of data in the table's column where it finds the data
     * Args table (table to search), column (where to find data), data (what to search for)
     * */
    public static void Delete(String table, String column, String data){
        Statement stmt;
        try {
            stmt = connection.createStatement();
            stmt.execute("DELETE FROM " + table + " WHERE " + column +"=" + data);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
