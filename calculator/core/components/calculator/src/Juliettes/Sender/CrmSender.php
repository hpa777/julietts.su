<?
namespace Juliettes\Sender;

use modX;


class CrmSender implements MessageSenderInterface { 

    protected $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function send(array $options, string $message) {
        $dsn = $this->modx->getOption('crm_dsn');
        if (empty($dsn)) return;
        try {
            $id = time();
            $pdo = new \PDO($dsn);            
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            // Подготовка данных для вставки
            $bracelet = 0;
            $stoneprice = 0;
            $engravingTexts = [];
            $productDescriptions = [];
            $stoneName = '';
            
            foreach($options['items'] as $item) {
                $bracelet += $item['price']; 
                if (!empty($item['stoneName'])) {
                    $stoneprice += $item['stonePrice'] - $item['price'];
                    $stoneName = strip_tags($item['stoneName']);
                }
                if (!empty($item['text'])) {
                    $engravingTexts[] = $item['text'];
                }
                $productDescriptions[] = $item['name'];
            }
            
            // Добавляем цепь в описание продукта
            $productDescriptions[] = 'Цепь: ' . $options['chain']['name'];
            
            // Объединяем описания продуктов и гравировки
            $productDescription = 'Браслет из ' . $options['chain']['tabname'] . ': ' . implode('; ', $productDescriptions);
            $engravingText = implode('; ', $engravingTexts);
            
            // Вставляем все данные в одну таблицу
            $sql = 'INSERT INTO public.jewelry_orders_complete (
                id_tilda, customer_name, customer_street, customer_email, customer_phone, 
                customer_comment, order_date, order_status, order_type, total_cost, 
                metal_type, product_description, stone_type, engraving_text, chain_type, 
                item_price, chain_price, stones_price, engraving_price, final_amount, image, length, source
            ) VALUES (
                :id, :name, :addr, :email, :phone, :comment, :order_date, :status, 
                :order_type, :total_cost, :metal_type, :product_description, :stone_type, 
                :engraving_text, :chain_type, :item_price, :chain_price, :stones_price, 
                :engraving_price, :final_amount, :image, :length, :source
            );';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $options['name'] ?? '',
                ':addr' => $options['address'] ?? '',
                ':email' => $options['email'] ?? '',
                ':phone' => $options['phone'] ?? '',
                ':comment' => $options['text'] ?? '',
                ':order_date' => date('Y-m-d'),
                ':status' => 'Новый',
                ':order_type' => 'Браслет',
                ':total_cost' => $options['cost'] ?? 0,
                ':metal_type' => $options['chain']['tabname'] ?? '',
                ':product_description' => $productDescription,
                ':stone_type' => $stoneName,
                ':engraving_text' => $engravingText,
                ':chain_type' => $options['chain']['name'] ?? '',
                ':item_price' => $bracelet + ($options['chain']['price'] ?? 0),
                ':chain_price' => $options['chain']['price'] ?? 0,
                ':stones_price' => $stoneprice,
                ':engraving_price' => $options['engraving'] ?? 0,
                ':final_amount' => $options['cost'] ?? 0,
                ':image' => $this->modx->getOption('site_url') . 'assets/orders/images/' . $options['image'],
                ':length' => $options['chain']['length'],
                ':source' => 'Конструктор'
            ]);
            $this->modx->log(1, 'order successful add');
    
        } catch (\PDOException $e) {            
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage()); 
        }
    }

/*
    public function send(array $options, string $message) {
        $dsn = $this->modx->getOption('crm_dsn');
        if (empty($dsn)) return;
        try {
            $id = time();
            $pdo = new \PDO($dsn);            
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Set to throw exceptions on errors
            $sql = 'INSERT INTO public.address (id_tilda, "Name", "Street", email, contact1) 
            VALUES (:id, :name, :addr, :email, :phone);';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $options['contact']['name'],
                ':addr' => $options['contact']['address'],
                ':email' => $options['contact']['email'],
                ':phone' => $options['contact']['phone'],
            ]);

            $sql = 'INSERT INTO public."order" (id_tilda, "DateOrder", "Status_order", "Revenue product", tip_zakaza, coment_client) 
            VALUES (:id, :dte, :tp, :cost, :it, :txt);';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':dte' => date('Y-m-d'),
                ':cost' => $options['cost'],
                ':txt' => $options['contact']['text'],
                ':tp' => 'Новый',
                ':it' => "Браслет"
            ]);

            $sql = 'INSERT INTO public.goods (id_tilda, metal, "tip_stones", "Product description", "Engraving", "typechain") 
            VALUES (:id, :mtl, :stn, :dsc, :txt, :chn);';
            $stmt = $pdo->prepare($sql);
            $bracelet = 0;
            $stoneprice = 0;
            foreach($options['items'] as $item) {
                $bracelet += $item['price']; 
                if (!empty($item['stoneName'])) {
                    $stoneprice += $item['stonePrice'] - $item['price'];
                }
                $params = [
                    ':id' => $id,
                    ':mtl' => $options['chain']['tabname'],
                    ':dsc' => $item['name'],
                    ':chn' => ''
                ];                
                $params[':stn'] = !empty($item['stoneName']) ? $item['stoneName'] : '';
                $params[':txt'] = !empty($item['text']) ? $item['text'] : '';
                $stmt->execute($params);
            }
            $params = [
                ':id' => $id,
                ':mtl' => $options['chain']['tabname'],
                ':dsc' => 'Цепь',
                ':chn' => $options['chain']['name'],
                ':stn' => '',
                ':txt' => ''
            ];
            $stmt->execute($params);

            $sql = 'INSERT INTO public.jewelry_orders (
                id_tilda, item_price, chain_price, stones_price,
                engraving_price, total_amount, final_amount
            ) VALUES (:id, :itm, :chn, :stn, :eng, :cst, :cst);';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':itm' => $bracelet + $options['chain']['price'],
                ':chn' => $options['chain']['price'],
                ':stn' => $stoneprice,
                ':eng' => empty($options['engraving']) ? 0 : $options['engraving'],
                ':cst' => $options['cost']
            ]);

            $sql = 'INSERT INTO public.engraving (id_tilda, description, sum) 
            VALUES (:id, :txt, :cst);';
            $stmt = $pdo->prepare($sql);
            foreach($options['items'] as $item) {
                if (!empty($item['text'])) {
                    $stmt->execute([
                        ':id' => $id,
                        ':txt' => $item['text'],
                        ':cst' => $options['$cost_engraving']
                    ]);
                }
            }
    
        } catch (\PDOException $e) {            
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage()); 
        }
    }
        */
}