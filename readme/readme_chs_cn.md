**翻译:**
中文(简体)
 | [English](/README.md)
 | [Français](readme_fre_fr.md)
 | [Deutsch](readme_ger_de.md)
 | [Indonesia](readme_ind_id.md)
 | [Italiano](readme_ita_it.md)
 | [日本語](readme_jpn_jp.md)
 | [Português (Brasil)](reamde_por_br.md)
 | [Русский](readme_rus_ru.md)
 | [Español](readme_spa_xm.md)
 | [Türkçe](readme_tur_tr.md)

# swgoh-comlink-for-github
该版本用于从SWGoH Comlink获取数据并存储在GitHub上，因此可以用于个人项目而不需要在服务器上托管Comlink。
 
要开始使用，请按照下面列出的步骤进行。

## 步骤1
前往GitHub并 [注册一个账户](https://github.com/signup). 在创建你的用户名和密码后，它将在验证你的电子邮件后问你几个问题。第一个问题是问你的团队有多少人，只选择你，然后点击继续。在下一个问题屏幕上，你可以直接点击继续。在这之后，它会给你计划选项，选择免费计划，点击继续免费。 
 
## 第2步
一旦你登录了Fork这个版本库，点击窗口右上角Watch和Star选项之间的Fork选项。

## 第3步
下载并安装 [GitHub桌面](https://desktop.github.com/) 为你的计算机。安装完毕后，打开GitHub桌面并登录。\
接下来选择克隆版本库，克隆你为这个版本库制作的分叉版本。这将在你的电脑上放置一个副本。

## 第4步
将[SWGoH Comlink](https://github.com/sw-goh-tools/swgoh-comlink/releases)的最新二进制文件版本下载到你的电脑。

## 第5步
如果还没有安装，请为你的操作系统下载最新版本的[PHP](https://www.php.net/downloads)。
### 安装PHP的步骤
1. 要看你是否安装了php，你可以使用cmd.exe或终端机进入命令行，输入`php -v`。如果你已经安装了它，它将显示你的版本信息，你可以跳到3。
2. 如果你没有安装PHP，请从上面的链接中下载合适的文件。对于Windows来说，你要抓取最新的稳定版本，上面写着Thread Safe。如果你有一个32位的操作系统，你将抓取标题中带有 "x86 Thread Safe "的文件，对于64位操作系统，你将抓取标题中带有 "x64 Thread Safe "的文件。
3. 在你安装/解压文件到你想要的目录后，你可能需要设置环境变量来识别它。在Windows的搜索栏中输入环境变量，选择编辑系统环境变量的选项。在弹出的窗口底部点击环境变量。你需要选择`Path`变量，然后选择编辑。在php.exe文件的完整文件路径后添加一个;。在上面的方框和下面的方框中都这样做。
4. 进入命令行工具，输入`php -v`以验证PHP是否正确安装并正常工作。

## 第6步
在windows系统的搜索框中使用_cmd.exe或在linux/mac系统中使用_terminal来打开你的命令行工具。运行Comlink，输入完整的文件路径，然后输入`--名称 "Comlink for GitHub"`。如果你愿意，你可以使用任何你想要的名字。\
***例子:***\
`C:\Users\Kidori\Desktop\Swgoh Comlink\swgoh-comlink-1.3.0.exe --name "Comlink for GitHub"`

## 第7步
打开一个新的命令行工具窗口。你将使用这个窗口来运行php scrpts来创建/更新你的 repo中的数据。你可以通过输入`php -r "require 'File Path'; function(arguments);"`来完成。你将需要使用这个 repo 的分叉副本的文件路径，然后输入你想运行的函数。\
**例子**\
`php -r "require 'C:\Users\Kidori\Desktop\GitHub\swgoh-comlink-for-github\run.php'; getPlayerData(596966614);"`

### run.php的可用函数
#### getPlayerData(allyCode, host, port, username, password)
检索属于所提供的盟友代码的玩家资料。\
**参数:**
```
 allyCode {字符串/整数} - 该玩家的盟友代码
 host {绳子} | 可选 -SWGOH Comlink正在运行的网络地址。默认为http://localhost。
 port {绳子} | 可选 - SWGOH Comlink所运行的端口。默认为3000
 username {绳子} | 可选 - SWGOH Comlink需要的访问密钥或用户名（如果有的话
 password {绳子} | 可选 - SWGOH Comlink所需的密匙或密码（如果有）。
```

#### getGuildData(allyCodes, host, port, username, password)
检索属于所提供的每个指定盟友代码的多个球员资料。\
**参数:**
```
 allyCodes {阵列} - 每个盟友在最多两个行会的代码
 host {绳子} | 可选 -SWGOH Comlink正在运行的网络地址。默认为http://localhost。
 port {绳子} | 可选 - SWGOH Comlink所运行的端口。默认为3000
 username {绳子} | 可选 - SWGOH Comlink需要的访问密钥或用户名（如果有的话
 password {绳子} | 可选 - SWGOH Comlink所需的密匙或密码（如果有）。
 ```

#### getGameData(filtered, allCollections, host, port, username, password)
检索与游戏的不同方面有关的数据集合。\
**参数:**
```
 filtered {bool} | 可选 - 只返回每个集合中需要的最有用的数据以减少文件大小。默认为真。
 allCollections {bool} | 可选 - 返回游戏可能使用的所有数据集合。默认为false。
 host {绳子} | 可选 -SWGOH Comlink正在运行的网络地址。默认为http://localhost。
 port {绳子} | 可选 - SWGOH Comlink所运行的端口。默认为3000
 username {绳子} | 可选 - SWGOH Comlink需要的访问密钥或用户名（如果有的话
 password {绳子} | 可选 - SWGOH Comlink所需的密匙或密码（如果有）。
```

#### buildTerritoryBattles(lang)
创建领土争夺战文件。\
**参数:**
```
lang {绳子} | 可选 - 你想使用的语言，不是所有的数据都能被翻译。默认为ENG_US
  选择: CHS_CN, CHT_CN, ENG_US, FRE_FR, GER_DE, IND_ID, ITA_IT,
               JPN_JP, KOR_KR, POR_BR, RUS_RU, SPA_XM, THA_TH, TUR_TR
```

## 第8步
运行这些功能后，您需要使用 GitHub Desktop 发布对克隆 repo 版本所做的更改。进入GitHub桌面，选择swgoh-comlink-for-github repo（如果还没有选择）。它应该会显示任何待修改的文件列表，为这些修改添加一个摘要，然后点击提交到主目录。现在你需要点击推送原点，将提交的内容推送到 GitHub。

## 第9步
现在你可以通过获取原始文件的网络地址在Google Sheets中检索更新的信息。你可以通过在互联网浏览器中点击 repo中的json文件，并点击文件编辑器窗口顶部的Raw按钮来获得这个网址。\
**例子**\
`https://raw.githubusercontent.com/Kidori78/swgoh-comlink-for-github/data/platoons.json`
