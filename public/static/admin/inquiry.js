var guahaoTable,inquiryTable,select_treat_id;
var recorder;
var video='';
var annotationNumber=0;
var jsonQuestion = '';
var record_state = '';
var fuDaiNumbers;
var selectedRecipeId = 0;

var biaozheng_default = "不发热， 发热， 发热无汗， 发热头痛， 身黄发热， 发热不止，久热不退，热不去，身热不去， 无大热，身有微热， 热多寒少， 身热恶风， 潮热，身大热， 日晡所（日将落）发热，日晡所发潮热，日晡所小有潮热， 暮即发热， 发热谵语， 伤寒发热汗出不解， 表有热， 里有热， 四肢苦烦热， 手脚发烧， 五心发烧， 手足烦热， 足下热， 历节病两胫足温， 往来寒热， 不恶风， 恶风，恶风不欲去衣，时时恶风， 不恶寒，不恶寒但热， 恶寒，身冷， 微寒，微恶寒，背微恶寒， 其背恶寒， 振寒（寒战）， 厥，逆冷，厥逆， 手足厥逆（四逆），手足厥冷， 手足寒， 手足厥， 四肢厥冷， 腰中冷，腰中冷如坐水中， 腰以下冷湿， 两胫自冷， 少腹如扇， 里寒外热， 往来寒热， 无汗， 汗出， 汗出而喘， 汗出不恶寒， 汗出而闷， 汗出而厥， 汗出恶风， 汗出恶寒， 汗出短气， 汗出而渴， 汗色黄如柏汁，黄汗， 黄汗病两胫自冷， 汗出已反发热， 自汗出，续自汗出， 微盗汗出，暮卧盗汗出， 多汗， 大汗出， 汗遂漏不止， 手足濈然汗出， 腰以上汗出，下无汗， 食已汗出， 额上生汗，但头汗出，但头汗出、身无汗，剂颈而还， 病溢饮，水饮流到四肢当汗出而不汗出， 发汗后， 发汗过多， 发汗后脐下悸， 发汗则谵语， 面色正常，面色薄，面色白， 面色黧黑，面目黧黑， 面色有热色，面翕热如醉状，面热如醉,为胃热上冲熏面，颜面潮红， 两目黯黑，目赤如鸠眼，目四眦黑， 额上黑， 黄家，黄疸，身黄，身尽黄，身发黄，身体尽黄，久久发黄，身黄发热， 知觉神经麻痹，面瘫， 麻痹不仁， 鼻鸣， 鼻燥， 项强，颈项强，头项强痛，项背强几几， 头痛，手足头痛， 身疼， 身疼重， 身肿痛， 身体疼烦， 一身尽疼， 身重， 身疼重， 身不疼但重， 身肿，身微肿， 身肿痛， 一身悉肿， 一身面目黄肿， 身瞤， 身瞤动，振振欲擗地， 身甲错，其身甲错，肌肤甲错，身体强几几， 身体尪赢， 全身抽搐， 身为振振摇， 赢瘦， 身麻痹不仁， 身难以转侧，不能自转侧， 但臂不遂， 半身不遂， 知觉神经麻痹， 麻痹不仁， 外证身体不仁（身体麻痹不仁），如风痹状（风痹要疼的　血痹不疼）， 口不仁， 酸削不能行，欲得按， 形如水状， 有物在皮中状，如有物在皮中， 腰痛， 腰以下冷痛， 大病瘥后腰以下有水气， 身痒，身痒而瘾疹， 外证疮疡，生恶疮，恶疮， 四肢肿， 四肢酸疼， 四肢沉重疼痛， 四肢聂聂动， 四肢微急难以屈伸 ， 手足不得屈伸,近之则痛剧， 手足烦， 手足痹， 手足不仁， 脚挛急， 脚肿如脱， 脚气疼痛,不可屈伸， 筋惕肉瞤， 身瞤胸中痛， 骨疼， 骨节疼痛，骨节疼烦掣痛（不得屈伸,近之则痛剧）， 关节痛， 支节烦疼， 诸肢节疼痛， 卧不着席， 风湿相抟，风湿相搏， 湿家身烦疼， 病历节不可屈伸,疼痛， 历节病两胫发热， 状如风水".split('，');
var lizheng_default = "不渴，口中和，发热不渴， 渴，思水（渴）， 大渴， 渴欲饮水，渴引水浆， 欲饮水数升，渴欲饮水不止， 烦渴，大烦渴不解， 消渴， 微热消渴， 消渴小便反多，以饮一斗，小便一斗， 口燥渴， 热多欲饮水， 手足温而渴， 舌上燥而渴， 身热而渴， 其人苦渴， 口干舌燥， 舌上干燥而烦， 渴欲饮水，水入则吐（水逆）， 不呕， 呕，呕吐， 欲呕，欲呕吐， 不下利但呕， 呕不止， 呕而发热， 呕而胸（腹)满， 呕而肠鸣， 呕不能食，呕不能饮食， 呕家不渴， 呕吐而下利， 呕而心下痞和下利， 呕吐而病在膈上， 呕吐,心下痞,眩悸， 先渴后呕， 胃反（中虚而呕吐），胃反呕吐， 食谷欲呕，得食而呕又烦者， 腹痛而呕， 吐， 吐逆， 吐利， 吐水，水入则吐， 吐血，吐血不止， 吐涎沫， 吐而渴欲饮水， 时欲吐，温温欲吐， 食入即吐，食已即吐（吃完就吐），寒格更逆吐下，食入口即吐， 气逆欲吐， 一动就要吐， 腹满欲吐， 干呕， 干呕烦，干呕心烦不得安， 干呕而利， 干呕短气， 哕（干呕频繁连续）， 腹满而喘，热除必哕， 噎， 噫气， 哕逆， 逆气， 干噫食臭， 心下逆满， 打嗝嗳气， 口疮，口腔溃疡， 心胸中有停痰宿水， 下利， 微利， 久利，久痢， 利不止，下利不止，利遂不止，利未欲止， 自下利， 自利,利反快，虽利，心下续坚满， 下利复发， 下利已差， 下利谵语， 下利呕逆， 下利清谷， 下利不饮食（有宿食）， 下利日数十行， 下利大实痛， 下利后更烦， 下利脉反滑， 下利腹满时痛， 下利欲饮水（下利而渴）， 下利厥逆而恶寒， 大下利而厥冷， 下利后烦按之心下濡， 腹中雷鸣下利， 肠鸣， 热利， 热下利血便， 下利便脓血， 痢疾， 泄利下重，里急后重， 协热而利， 热利下重，里急后重， 脉微下利， 太阳少阳合病下利， 气利（冷气虚恭）， 里实， 不大便， 大便硬，大便坚， 大便色黑，大便色黑，时溏， 屎硬大便反易， 大便坚硬不通， 痛而闭者（腹胀满而痛，大便不通）， 谷道不通（大肠不通）， 支饮胸满（有于大便不通）， 先便后血， 先血后便，痔疮出血， 小便不利， 遗尿， 谵语遗尿， 小便难， 小便赤（里热）， 小便利， 小便数， 小便热痛，小便热辣辣痛， 小便艰涩， 小便自调， 小便不利而赤， 小便欲自利， 小便余沥不尽， 无汗小便反少。 中虚小便频，小便自利（小便频数）， 消渴小便反多，以饮一斗，小便一斗， 小便如粟状，小腹弦急，痛引脐中， 能食，饮食如故， 消谷喜饥， 热结在里， 不能食，谷不化， 寒热不食， 谷不得下（吃不下东西)， 食即头眩， 食已汗出， 食则谵语， 但欲饮热， 嘿嘿不欲饮食， 胃中不和心下痞硬， 胃中有邪气 心下坚，心下硬， 心下痞硬，心下痞坚，心下痞硬满， 心下濡（按之濡）， 心下悸， 心下急，胸胁满微结， 心下逆满， 心中结痛， 心下痛按之石硬，正在心下按之则痛，心下痛不可近， 心下满微痛， 心下满而硬痛， 心下满而不痛， 心下痞， 心下痞按之濡， 心下至少腹硬满， 结胸， 胃疼， 不结胸， 胃里有寒， 心中热（胃中热）， 心中热，黄疸腹满， 胃中苦浊， 虚寒胃痛， 叉手自冒心， 胃气不和谵语， 口疮， 口腔溃疡， 瘀热在里， 腹满， 腹微满， 腹胀满， 腹满如故， 腹满而喘， 腹满身重(难以转侧)， 腹满，脉浮而数， 腹大满不通， 腹胀大实大满大痛， 腹满（肚子胀）口舌干燥， 腹中雷鸣，腹满胁鸣相逐， 胸中痞硬， 心烦腹满， 腹无积聚， 腹胀如水状， 心胸间虚，气满，多唾口燥， 心中烦闷， 胸腹逆满烦乱， 腹痛， 腹中痛， 腹中急痛， 心胸中大寒痛（痛而不可触）， 腹中寒气，雷鸣切痛， 腹重，腹重如带五千钱， 腹中寒， 胁下偏痛， 寒疝绕脐痛，寒疝腹中痛， 脐下悸，脐下有悸， 腹皮急（肚皮拘急痉挛），按之濡（软），如肿状，腹皮急， 病癥瘕， 下之后，若吐若下后，大下之，若吐若下，下血， 久出血".split('，');
var    biaoli_default = "咽干，咽中干， 口干舌燥， 咽中伤生疮，不能语言，声不出者， 咽干口燥， 咽干不渴， 咽中如有炙肉， 咽中如有炙肉，吐之不出，吞之不下， 喉中涩，噎塞习习如痒， 火逆上气，咽喉不利， 气上冲胸， 气上冲喉咽， 气从小腹上冲胸咽， 气从少腹上冲心， 头眩，目瞑，目眩（目眶痛），头晕，头冒， 苦冒眩，头冒眩，时复冒， 起则头眩， 目不得闭， 面目乍赤，乍黑，乍白， 头眩短气， 目如脱状， 口苦， 齘齿， 胸满， 胸满胁痛， 胸满而短气， 咽痛，胸满，心烦， 胸中窒， 胸痹，胸中气塞， 胸中气塞， 胸中有热， 胸背痛， 胸痹心痛， 胸痹心中痞，留气结在胸， 胸痹缓急（时轻时重）， 胸痹不得卧， 常欲蹈其胸上， 心痛彻背， 心痛彻背，背痛彻心， 心胸不安， 心中痞， 诸逆心悬痛， 心中懊侬， 心动悸， 心下悸， 胸胁满微结，心下急， 引胁下痛， 胁下痞硬， 胁下满， 胸胁支满， 胁下逆抢心， 胸胁逆满， 左胁下脾肿大， 热痛（肝区灼热痛，或者胆热痛）， 胸胁苦满， 胸胁满而呕， 心下坚大如盘，边如旋盘， 心下支结， 心中悸而烦， 少腹弦急（比里急还厉害）， 少腹急结， 少腹拘急（小腹痉挛，抽搐）， 少腹里急， 热流膀胱，热结膀胱， 膀胱急， 虚劳腰痛， 少腹硬， 少腹满， 少腹硬满， 少腹肿痞，按痛如淋， 咳，咳家， 短气， 咳而微喘， 咳逆上气，咳而上气，咳而上气，喉中水鸡声， 咳有微热，烦燥胸满， 咳而脉浮， 咳而脉沉， 咳而胸满， 时时吐浊， 喘， 微喘， 喘满， 喘而胸满者， 烦躁而喘， 喘息咳唾， 肺痈（喘不得卧，咳而胸满），浓未成， 时出浊唾腥臭，久久吐脓如米粥， 微喘直视， 心下有痰饮（微则短气，甚则悸）， 悬饮内痛， 咳家，咳烦，胸中痛， 病支饮（咳逆倚息，短气不得卧，其形如肿)，支饮不得息（痰饮充斥，压迫肺，呼吸困难），吐涎沫而癫眩， 咳逆，倚息不得卧， 冲气即低，而反更咳，胸满，咳满， 咳满即止，而更复渴，冲气复发， 烦，烦躁， 微烦， 烦热， 虚烦， 虚劳虚烦不得眠， 心中烦， 心烦烦躁， 心烦喜呕， 心烦腹满， 短气躁烦， 烦躁欲死， 郁郁微烦， 四肢苦烦（手足自温）， 卧起不安， 昼日烦躁不得眠， 不汗出而烦躁虚烦不得眠， 汗出而闷， 难以转侧， 但坐不得眠， 不能卧但欲起， 疯， 惊， 狂，惊狂，发狂， 其人如狂，其人发狂， 癫， 大汗亡阳惊狂， 谵语， 不得卧， 不识人， 循衣摸床， 惕而不安， 发热谵语， 谵语发潮热， 口噤不得语， 独语如见鬼状， 口噤， 百合病（静默不言不语，昏昏然，卧起不安，饮食无常，冷热不定，口苦，小便赤，精神失常，癫，）， 狐惑病（默默欲眠，卧起不安，蚀于喉为惑，蚀于阴为狐，不欲饮食，恶闻食臭，蚀于上部则声喝，蚀于下部则咽干）， 嗜卧， 但欲寐， 虚劳不足， 夜而安静， 默默但欲卧， 虚赢少气， 大病瘥后劳复， 如虐状，形似疟， 温疟者其脉如平，身无寒但热，骨节疼烦，时呕， 疟多寒者名日牡疟， 疟病发渴， 治疟寒多微有热，或但寒不热， 短气， 不得息， 汗出短气， 精自出， 男子失精， 梦失精， 阴寒精自出， 阴头寒， 发落， 女子梦交， 虚劳里急（里急就是少腹里急）， 肝着(瘀滞）， 衄血， 上焦有热（颜面潮红即脸红，唇红，心烦，心悸，）， 小儿鼻衄， 心气不足（心悸，心烦，不安定）， 其人喜忘， 邪火内炽，吐血，衄血，三焦积热，眼目赤肿，口舌生疮，外证疮疡， 下血， 久出血， 瘀热在里， 有久瘀血， 妇人 经断， 崩漏，妇人漏下， 漏下不止， 妇人崩漏下血， 血自下， 子宫下血， 妇人月经不调下血， 带下（下血不止，白带）， 妇人月经不调， 妇人月经过多， 妇人月经至期不来， 经水不利， 月经一月再见， 月经提前（多有热）， 月经延后（多有寒）， 妇人经血不利， 月经中断， 妇人五十病下血不止， 妇人经水不利下（经闭），经闭不利下， 妇人久不受胎， 气冲， 心悸，心悸动， 其人如狂， 起即头眩， 热结膀胱， 少腹里急， 少腹急结， 热入血室，昼而安静，夜如见鬼状， 如疟状，往来寒热， 发作有时， 妇人脏躁（指的心脏）， 喜悲伤欲哭，悲伤喜哭， 多梦， 精神离散， 烦躁不安， 夜间哭闹， 手掌烦热， 唇口干燥， 昼日明了， 夜则谵语， 一到夜间就发热， 血虚生内热， 五心烦热， 妊娠下血， 妊娠腹痛， 妊娠小便难， 妊娠呕吐不止， 妊娠身重浮肿， 半产后续下血，半产下血， 腹中绞痛， 小便艰涩， 小便热痛，小便热辣辣痛， 慢性泌尿感染， 小便余沥不尽， 血虚而厥， 少腹满痛， 子宫有寒， 其人赢瘦， 呕而不能食， 热下利血便， 小便微难而不渴， 不得溺， 转胞， 胞系， 了戾， 子宫下垂， 胎动脐上， 癥痼，癥块， 妇人少腹寒， 妇人少腹满， 妇人乳中虚，烦乱呕逆， 咽中如有炙肉，吐之不出，吞之不下妇人曾经半产， 妇人烦热不得卧， 妇人阴吹， 阴中生疮， 阴中有寒， 阴中湿痒， 产后中风， 产妇腹痛， 产妇郁冒， 产妇喜汗出者， 烦满不得卧， 产后少腹坚痛， 产后恶露不尽， 产后下利虚极， 妇人产后虚羸不足，腹中刺痛不止，痛引腰背,不能食饮， 男子膀胱满急有淤血".split('，');
var maizheng_default = "脉缓，脉浮缓，脉促，脉微缓，脉微，脉洪大，脉微弱，脉浮，脉沉，脉浮细，脉沉微，脉沉紧，脉浮数，脉微而沉，脉沉结，脉浮而动数，脉沉而紧，脉关上浮，寸脉微浮，脉浮，脉浮虚而涩，脉浮滑，脉结悸，脉结代，脉迟，脉弦，脉涩，脉滑，脉浮数，脉数，无脉，脉微欲绝，脉微而厥，脉细欲绝，脉弱，脉沉迟，脉浮虚而涩脉微而数，脉阴阳俱微，脉浮弱而涩，脉极虚芤迟，脉浮大，脉浮而数，脉紧弦，脉沉弦，脉伏，脉沉小，脉迟而涩，脉浮而缓，脉弱，脉平，脉迟而滑，阳脉涩，阴脉弦".split('，');

function drawGuahaoTable() {
    if(guahaoTable)
        guahaoTable.destroy();
    guahaoTable = $('#tbl_guahao').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'guahao_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "bFilter": false,
        info: false,
        select: true,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/inquiry/getGuahao',
            "dataType":"json"
        },
        columns: [
            {data: 'guahao'},
            {data: 'patient_name'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [0],
                'orderable': false,
                "mRender": function (data, type, full) {
                    switch (data) {
                        case 'ACCEPT':
                            return '挂号'
                            break;
                        case "ACCEPT_PAY":
                            return '挂号付费'
                            break;
                        case "WAITING_TREATMENT":
                            return '等待问诊'
                            break;
                        case "TREATING":
                            return '在问诊中'
                            break;
                        case "BEFORE_TREATING_PAY":
                            return '完成问诊'
                            break;
                        case "AFTER_TREATING_PAY":
                            return '支付药费'
                            break;
                        case "BEFORE_MEDICINE":
                            return '等待抓药'
                            break;
                        case "CLOSE":
                            return '回家'
                            break;
                        default:
                            return data;
                    }
                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback":function(settings){
            $( "#tbl_guahao tbody tr:first-child" ).trigger('click');
        }

    });
}
function drawInquiryTable(treat_id){
    if(inquiryTable)
        inquiryTable.destroy();
    inquiryTable = $('#tbl_inquiry').DataTable({
        "processing":true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'treat_' + aData.id); // or whatever you choose to set as the id
        },
        "serverSide":true,
        "order": [[0, "desc"]],
        "pageLength":10,
        "bFilter": false,
        info: false,
        "aLengthMenu":[10,20,50],
        "ajax":{
            "type":"POST",
            "url": '/doctor/inquiry/getTreatementData',
            'data':{id:treat_id},
            "dataType":"json"
        },
        columns: [
            {data: 'patient_name'},
            {data: 'number'},
            {data: 'date'},
            {data: 'recipe'},
            {data: 'id'}
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json"
        },
        "aoColumnDefs": [
            {
                "aTargets": [2],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return data==null||data==''||data==undefined?data:data.substring(0, 10);;
                }
            },{
                "aTargets": [4],
                'orderable': false,
                "mRender": function (data, type, full) {
                    return '<button class="btn btn-sm btn-success m-l-5" onclick="location.href=\'/doctor/history/detail/' + data+ '\'"><i class="ti-pencil-alt"></i>详情</button>';

                }
            },
            {"className": "text-center", "targets": "_all"}
        ],
        "drawCallback":function(settings){
            $('#tbl_guahao tbody').on( 'click', 'tr', function () {
                guahaoTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            });
        }

    });

}
$(function () {
    if($( "#tbl_guahao" ).length)
        drawGuahaoTable();
    if(record_state==''&&$("#myVideo").length){
        Swal.fire({
            type: 'warning',
            title: '请开始录制视频。',
            showConfirmButton: false,
            timer: 3000
        });
    }

    if($("#question_title").length)
        $("#question_title").prepend("<option></option>").select2({
            allowClear: true,
            placeholder:"请选择问诊单模板"
        });
    if($("#recipe").length){
        $("#recipe").prepend("<option></option>").select2({
            placeholder:"请选择",
        });
    }
    if($("#search_history").length){
    $("#search_history").prepend("<option></option>").select2({
        allowClear: true,
        placeholder:"请选择问诊单模板"
    });
    }
    if($("#medicine").length)
        $("#medicine").select2();

    if($("#myVideo").length)
        startVideoCamera();
    $("#question_title").on("change",function(e){
        var data = $(this).val();
        if(data==''||data==null||data==undefined) {
            // default list
            drawItems('biaozheng',biaozheng_default,[], false);

            drawItems('lizheng',lizheng_default,[], false);
            drawItems('biaoli',biaoli_default,[], false);
            drawItems('maizheng',maizheng_default,[], true);
            init_recipeSection();
            return;
        } else {
            init_recipeSection();
            showOverlay();
            $.ajax({
                url: '/doctor/inquiry/getRecipe',
                data: 'question_id='+data,
                type: 'POST',
                cache: false,
                dataType: 'json',
                processData: false,
                success: function (resp) {
                    hideOverlay();
                    if (resp.code == 0) {
                        drawSlide(JSON.parse(resp.data.question.questions));
                        jsonQuestion = JSON.parse(resp.data.question.questions);
                        var medicines = JSON.parse(resp.data.question.medicines);
                        for(var i =  0; i < medicines.length; i++){
                            $("#recipe").append(new Option(medicines[i].prescription_name,medicines[i].id,false,true));
                        }
                        $("#recipe").prop('disabled',true);
                        $("#medicines").val(resp.data.question.medicines);

                        drawMedicine(medicines,true,false);

                        $("#question_string").val(resp.data.question.questions);
                        var biaozhengList = JSON.parse(resp.data.question.biaozheng)
                        drawItems('biaozheng',biaozhengList,[], false);
                        drawItems('lizheng',JSON.parse(resp.data.question.lizheng),[], false);
                        drawItems('biaoli',JSON.parse(resp.data.question.biaoli),[], false);
                        drawItems('maizheng',JSON.parse(resp.data.question.maizheng),[]);

                        // fuDaiNumbers = JSON.parse(resp.data.question.fuDaiNumber);
                    } else {
                        hideOverlay();
                        Swal.fire({
                            type: 'error',
                            text: resp.message,
                            title: '错误',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },
                error: function (e) {
                    hideOverlay();
                    Swal.fire({
                        type: 'error',
                        text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

    });

    $("#recipe").on("change",function (e) {
        hideTooltip();
        var recipes = $(this).val();
        drawRecipeSections(recipes,true);
    });

    // default slide
    drawSlide([]);

    // default list
    // drawZhengSection('biaozheng',biaozheng_default,[]);

    drawItems('biaozheng',biaozheng_default,[]);
    drawItems('lizheng',lizheng_default,[]);
    drawItems('biaoli',biaoli_default,[]);
    drawItems('maizheng',maizheng_default,[]);

    appendAnnotation();
    if($( "#tbl_guahao" ).length){
        setInterval( function () {
            guahaoTable.ajax.reload();
        }, 120000 );
    }


    $('#tbl_guahao tbody').on( 'click', 'tr', function () {
        guahaoTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        select_treat_id = $(this).attr('id')==undefined?'':$(this).attr('id').replace('guahao_','');
        drawInquiryTable(select_treat_id);

        var rowIndex = guahaoTable.row(this).index();
        var rowData = guahaoTable.rows( rowIndex ).data()[0];
        if(rowData!=undefined&&rowData!=null&&rowData!=''){
            $("#patient_name").val(rowData.patient_name);
            $("#ID_Number").val(rowData.ID_Number);
        }
    });

    scrollSidebarTop();



});

function createInquiry() {
    if(select_treat_id==''||select_treat_id==undefined||select_treat_id==null)
        return;
    window.location.href = '/doctor/inquiry/create/'+select_treat_id;
}

function startVideoCamera(){
    var videoMaxLengthInSeconds = 3600;

    // Inialize the video player
    var player = videojs("myVideo", {
        controls: true,
        width: 80,
        height: 45,
        fluid: false,
        autoplay:false,
        plugins: {
            record: {
                audio: true,
                video: true,
                maxLength: videoMaxLengthInSeconds,
                debug: true,
                videoMimeType: "video/mp4"
            }
        }
    }, function(){
        videojs.log(
            'Using video.js', videojs.VERSION,
            'with videojs-record', videojs.getPluginVersion('record'),
            'and recordrtc', RecordRTC.version
        );
    });
    player.on('deviceError', function() {
        console.log('device error:', player.deviceErrorCode);
    });
    player.on('error', function(error) {
        console.log('error:', error);
    });
    player.on('startRecord', function() {
        var guahao = $("#guahao").val();
        showOverlay();
        $.ajax({
            url: '/doctor/inquiry/startTreatment',
            data: 'guahao='+guahao,
            type: 'GET',
            cache: false,
            dataType: 'json',
            processData: false,
            success: function (resp) {
                hideOverlay();
                if (resp.code == 0) {
                    console.log(resp.data);
                    record_state = 'onRecording';
                } else {
                    Swal.fire({
                        type: 'error',
                        text: resp.message,
                        title: '错误',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            error: function (e) {
                hideOverlay();
                Swal.fire({
                    type: 'error',
                    text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });
    // user completed recording and stream is available
    // Upload the Blob to your server or download it locally !
    player.on('finishRecord', function() {
        record_state = 'endRecording';
        showOverlay();
        var videoBlob = player.recordedData;
        var formData = new FormData();
        formData.append('video', videoBlob);
        xhr('/doctor/inquiry/uploadVideo', formData, function (fName) {
            hideOverlay();
            video = fName.data;
            $("#video_url").val(fName.data);
        });
    });
    player.on('ready',function () {
        console.log('player is ready');
        //  $(".vjs-record-button").trigger('click');
    });
    player.play();
}

function xhr(url, data, callback) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            callback(JSON.parse(request.responseText));
        }
    };
    request.open('POST', url);
    request.send(data);
}
function appendAnnotation() {
    annotationNumber++;
    var html ="<div class=\"form-group mt-3 row\">\n" +
        "\t<div class=\"col-2 text-right\">\n" +
        "\t\t<button type=\"button\" class=\"btn btn-default\" title=\"删除\" data-index=\""+annotationNumber+"\" onclick=\"removeAnnotation(this);\"><i class=\"fas fa-times\"></i> </button>\n" +
        "\t</div>\n" +
        "\t<div class=\"col-3\">\n" +
        "\t\t<input class=\"form-control\" type=\"text\" name='annotation_key[]' id=\"annotation_key"+annotationNumber+"\">\n" +
        "\t</div>\n" +
        "\t<div class=\"col-3\">\n" +
        "\t\t<input class=\"form-control\" type=\"text\"  name='annotation_value[]' id=\"annotation_value"+annotationNumber+"\">\n" +
        "\t</div>\n" +
        "</div>\n"
    $("#annotationSection").append(html);
}

function removeAnnotation(obj) {
    annotationNumber--;
    var selectNumber = $(obj).data("index");
    var row = $(obj).parent().parent();
    row.remove();
    for(var i=selectNumber+1; i< annotationNumber+2; i++){
        $('*[data-index="'+i+'"]').parent().next().first().children().attr("id","annotation_key"+(i-1));
        $('*[data-index="'+i+'"]').parent().next().next().first().children().attr("id","annotation_value"+(i-1));
        $('*[data-index="'+i+'"]').attr("data-index",i-1);
    }

}

function drawRecipe(questions, recipes){
    var html = '',
        recipe_list = [];
    if (recipes.length > 0)
        recipe_list = JSON.parse(questions.medicines);
    for (var i = 0; i < recipe_list.length; i ++) {
        html += '<option value="' + recipe_list[i].receip_id+'" data-medicine=\'' + questions.medicines + '\' data-otherCondition=\'' +
            recipes[i].other_condition + '\' data-eating_method=\'' + recipes[i].eating_method + '\' data-ban=\'' + recipes[i].ban + '\'>'+recipe_list[i].receip_txt+'</option>';
    }
    $("#recipe").html(html);
}



function drawSlide(questions) {
    var html = '';
    var indicateHtml = '';
    for (var i = 0; i < questions.length; i++) {
        activeClass = i == 0 ? 'active' : '';
        html += '<div class="carousel-item ' + activeClass + '">\n' +
            '\t<div class="overlaybg"><img src="/img/photos/white.png" class="img-fluid"></div>\n' +
            '\t<div class="news-content carousel-caption text-dark">\n' +
            '\t\t<h4 class="text-dark">' + questions[i].question + '</h4>\n' +
            '\t\t<p>&nbsp;</p>\n';
        for (var j = 0; j < questions[i].answers.length; j++) {
            html += '\t\t<div class="custom-control custom-radio">\n' +
                '\t\t\t<input type="radio" id="customRadio' + i + '_' + j + '" name="customRadio' + i + '" class="custom-control-input slideRadio">\n' +
                '\t\t\t<label class="custom-control-label" for="customRadio' + i + '_' + j + '">' + questions[i].answers[j] + '</label>\n' +
                '\t\t</div>\n';
        }
        html += '\t</div>\n' +
            '</div>';
        indicateHtml += '<li data-target="#carouselExampleIndicators" data-slide-to="' + i + '" class="' + activeClass + '"></li>';
    }
    if(questions.length<1){
        html +='<div class="carousel-inner"><div class="carousel-item active">\n' +
            '\t<div class="overlaybg"><img src="/img/photos/white.png" class="img-fluid"></div>'
            +'</div></div>';
    }
    $(".carousel-inner").html(html);
    $(".carousel-indicators").html(indicateHtml);

    $('.slideRadio').click(function () {
        var index = $(this)[0].id.replace('customRadio','');
        var index_i = index.split('_')[0];
        var index_j = index.split('_')[1];
        jsonQuestion[index_i].selectIndex = index_j;
        $("#question_string").val(JSON.stringify(jsonQuestion));
    })
}

$('#question-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (typeof e.originalEvent === 'undefined' || e.isTrigger) {
        console.log('Prevent duplicate events');
        return false;
    }

    var form = $(this);
    if(!form.parsley({'excluded': ':disabled'}).validate()){
        return ;
    }

    if(record_state=='onRecording'){
        Swal.fire({
            type: 'warning',
            title: '请停止录制视频。',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    if($("#treatment_id").length)
        url = '/doctor/inquiry/update';
    else
        url = '/doctor/inquiry/completeTreatment';
    if(video==''){
        Swal.fire({
            type: 'error',
            title: '您尚未录制视频。'
        });
        return ;
    }
    showOverlay();
    var forms = new FormData($(this)[0]);

    var sel_houfang_length = 0;
    $("input[name='houfang[]']").each(function(index, obj){
        if ($(obj).val() == "1")
            sel_houfang_length ++;
    });
    if (sel_houfang_length > 3) {
        Swal.fire({
            type: 'warning',
            title: '是否合方不得超过3个。',
            showConfirmButton: false,
            timer: 3000
        });
        $("#recipe").val('').change();
        hideOverlay();
        return;
    }

    $.ajax({
        url: url,
        data: forms,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            hideOverlay();
            if (resp.code == 0) {
                hideOverlay();
                Swal.fire({
                    type: 'success',
                    text: '',
                    title: '成功',
                    showConfirmButton: false,
                    timer: 1500
                });
                if(url=='/doctor/inquiry/completeTreatment')
                    window.location.href = "/doctor/inquiry/view"
            } else {
                hideOverlay();
                Swal.fire({
                    type: 'error',
                    text: resp.message,
                    title: '错误',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        },
        error: function (e) {
            hideOverlay();
            Swal.fire({
                type: 'error',
                text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
                title: '错误',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
});

function init_recipeSection() {
    drawSlide([]);
    drawRecipe([], []);
    drawRecipeSections([], [], [], [], []);
    drawRecipeSectionsOther([], [], [], [], []);
}


function removeMedicineInq(recipeId, obj) {
    var id = $(obj).data("index");
    $("#medicine option[value='" + id + "']").attr("disabled",false);
    var row = $(obj).parent().parent().parent();
    row.remove();

    calcPriceOther(recipeId);
}
function changeFuNumber(obj) {
    var id = obj.id.replace('fuNumber_','');
    var fuNumber = $(obj).val();
    var strMedicines = $("#medicines").val();
    recipeDatas = JSON.parse(strMedicines);

    for(var i = 0 ; i < recipeDatas.length; i++){
        if(recipeDatas[i].id==id){
            recipeDatas[i].fuNumber = fuNumber;
            recipeDatas[i].price = calcPrice(recipeDatas[i].medicine)*fuNumber;
            break;
        }
    }
    $("#medicines").val(JSON.stringify(recipeDatas));
    if($("#question_title").val()==''||$("#question_title").val()==undefined||$("#question_title").val()==null)
        drawMedicine(recipeDatas,true,true);
    else
        drawMedicine(recipeDatas,true,false);
    calcPriceTotal();
}

// function searchRecipes(){
//     $("#recipe").prop('disabled',false);
//     if ($("#question_title").length > 0) {
//         var data = $("#question_title").val();
//         if(data==''||data==null||data==undefined) {
//
//             drawRecipeSections([], [], [], [], []);
//             drawRecipeSectionsOther([], [], [], [], []);
//
//             var forms = new FormData($("#question-form")[0]);
//
//             showOverlay();
//             $.ajax({
//                 url: '/doctor/inquiry/getRecipeOther',
//                 data: forms,
//                 type: 'POST',
//                 cache: false,
//                 contentType: false,
//                 processData: false,
//                 success: function (resp) {
//                     hideOverlay();
//                     if (resp.code == 0) {
//                         drawRecipeOther(resp.data);
//                     } else {
//                         hideOverlay();
//                         Swal.fire({
//                             type: 'error',
//                             text: resp.message,
//                             title: '错误',
//                             showConfirmButton: false,
//                             timer: 3000
//                         });
//                     }
//                 },
//                 error: function (e) {
//                     hideOverlay();
//                     Swal.fire({
//                         type: 'error',
//                         text: 'Internal Error ' + e.status + ' - ' + e.responseJSON.message,
//                         title: '错误',
//                         showConfirmButton: false,
//                         timer: 3000
//                     });
//                 }
//             });
//
//         } else {
//             return;
//         }
//     }
//
// }
