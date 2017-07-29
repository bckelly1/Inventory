package com.brian;

import com.brian.database.DatabaseConnector;

import java.sql.*;
import java.util.ArrayList;

/**
 *
 *
 * */

//import com.mysql.jdbc.Driver;

public class Inventory{
    static Connection conn = null;
    public static void main(String args[]) throws ClassNotFoundException {
        Connection conn = null;
        if(args.length > 0) {

            try {
                Class.forName("com.mysql.jdbc.Driver");
                conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/inventory", "com.brian.Inventory", "Br1@n$Inv#n70ryP@s$w0r9");
            } catch (SQLException e) {
                e.printStackTrace();
            }
            switch (args[0].toLowerCase()){
                case "schema":
                    DatabaseConnector.Schema(conn);
                    break;
                case "insert": //args String table, ArrayList data
                    if(args.length >= 3){
                        ArrayList<String> data = new ArrayList<>();
                        int count = 0;
                        for(String argument : args){
                            if(count > 1){
                                data.add(argument);
                            }
                            count ++;
                        }
                        DatabaseConnector.Insert(args[1], data);
                    }
                    else{
                        Usage();
                    }

                    break;
                case "update"://args String table, String column, String data, String matcherColumn, String matcherData
                    if(args.length == 6){
                        String table, column, data, matcherColumn, matcherData;
                        table = args [1];
                        column = args[2];
                        data = args[3];
                        matcherColumn = args[4];
                        matcherData = args[5];

                        DatabaseConnector.Update(table, column, data, matcherColumn, matcherData);
                    }
                    else{
                        Usage();
                    }
                    break;
                case "delete"://String table, String column, String data
                    if(args.length == 4){
                        String table, column, data;
                        table = args [1];
                        column = args[2];
                        data = args[3];

                        DatabaseConnector.Delete(table, column, data);
                    }
                    else{
                        Usage();
                    }
                    break;
                default:
                    String output = String.format("Unknown argument %s", args[0]);
                    System.out.println(output);
                    Usage();
                    break;
            }
            try {
                assert conn != null;
                conn.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
        else{
            Usage();
        }
    }

    public static void Usage(){
        System.out.println("Usage:");
        System.out.println("    com.brian.Inventory schema (lists the current schema)");
        System.out.println("    com.brian.Inventory insert [table] [values] (insert - requires table and comma separated data values)");
        System.out.println("    com.brian.Inventory update [table] [column] [value] (update - requires table, column, and data values)");
        System.out.println("    com.brian.Inventory delete [table] [column(s)] [value(s)] (delete - requires table, column, and data values)");
        System.exit(1);
    }
}