package com.example.mobadex.ui.main;

public class Session {

    private String Flag = "HackTM{\\_(*-*)_/\\_(moba_dex)_/}";
    private String token;
    private static Session instance = null;
    protected Session() {
        this.token = "";
    }
    public String getToken(){
        return this.token;
    }
    public String getFlag(){ return this.Flag; }
    public void setToken(String token){
        this.token = token;
    }
    public static Session getInstance() {
        if(instance == null) {
            instance = new Session();
        }
        return instance;
    }


}
