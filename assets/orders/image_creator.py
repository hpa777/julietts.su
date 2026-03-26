import time
import os
import configparser
import mysql.connector
import hashlib

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By

def set_viewport_size(driver, width, height):
    window_size = driver.execute_script("""
        return [window.outerWidth - window.innerWidth + arguments[0],
          window.outerHeight - window.innerHeight + arguments[1]];
        """, width, height)
    driver.set_window_size(*window_size)

if __name__ == "__main__":

    ENV = os.getenv("ENV", "prod")
    configFileName = 'config.ini' if ENV == 'prod' else 'config.dev.ini'
    path = os.path.dirname(__file__) + os.sep
    config = configparser.ConfigParser()
    config.read(path + configFileName)
   
    mydb = mysql.connector.connect(
        host = config['Mysql']['host'],
        user = config['Mysql']['user'],
        password = config['Mysql']['password'],
        database = config['Mysql']['database']
    )
    mycursor = mydb.cursor(dictionary=True)
    mycursor.execute("SELECT id FROM `qfgw_calculator_order` WHERE status=0 and image is null;")
    myresult = mycursor.fetchall()   
    if (len(myresult) == 0) :
        mycursor.close()
        mydb.close()
        raise SystemExit

    options = Options()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-gpu')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--headless')
    options.add_argument('--start-maximized')
    options.add_argument('--enable-unsafe-swiftshader')
    options.add_argument('--ignore-certificate-errors')
    options.add_argument('--ignore-ssl-errors')
    driver = webdriver.Remote(command_executor=config['Webdriver']['command_executor'], options=options)
    set_viewport_size(driver, 1908, 5000)

    try:
        for x in myresult:
            id = str(x['id'])            
            fileName =  hashlib.md5(f"{config['File']['name']}{id}{int(time.time() * 1000)}".encode('utf-8')).hexdigest() + ".png"
            url = config['Site']['url'] + id
            driver.get(url)
            time.sleep(1) 
            pathFile = config['File']['path'] + fileName            
            element = driver.find_element(By.ID, config['Site']['divid'])
            driver.execute_script('arguments[0].scrollIntoView({block: "center"});', element)
            if element.screenshot(filename=pathFile) == False :
                mycursor.execute("UPDATE `qfgw_calculator_order` SET `status`=5 WHERE `id`=%s", (id,))                
                continue        
            mycursor.execute("UPDATE `qfgw_calculator_order` SET `image`=%s, `status`=1 WHERE `id`=%s;",(fileName, id))           
                      
    except Exception as e:
        print(e)        
        pass
    finally:        
        mydb.commit()
        mycursor.close()
        mydb.close()
        driver.close()
        driver.quit()