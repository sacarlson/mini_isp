rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.beerA sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/beer/bitcoin.conf.beerA
rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.beerA /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.beerA
rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.beerA /home/sacarlson/bitcoin/multicoin/bitcoin.conf.beerA

rsync /home/sacarlson/bitcoin/multicoin/bitcoin.conf.weeds /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.weeds
rsync /home/sacarlson/bitcoin/multicoin/bitcoin.conf.weeds /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.weeds
rsync /home/sacarlson/bitcoin/multicoin/bitcoin.conf.weeds /home/sacarlson/bitcoin/multicoin-qt/doc/bitcoin.conf.weeds
rsync /home/sacarlson/bitcoin/multicoin/bitcoin.conf.weeds sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/weeds/bitcoin.conf.weeds

rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.mergmineTEST sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/mergemine/bitcoin.conf.mergmineTEST
rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.mergmineTEST /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.mergmineTEST

rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.namecoin sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/namecoin/bitcoin.conf.namecoin
rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.namecoin /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.namecoin
rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.namecoin /home/sacarlson/bitcoin/multicoin-qt/doc/bitcoin.conf.namecoin
rsync /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.namecoin /home/sacarlson/bitcoin/multicoin/doc/bitcoin.conf.namecoin

rsync /home/sacarlson/.bitcoin/mm2TEST/bitcoin.conf.mm2TEST /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.mm2TEST
rsync /home/sacarlson/.bitcoin/mm2TEST/bitcoin.conf.mm2TEST sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/mm2TEST/bitcoin.conf.mm2TEST
rsync /home/sacarlson/.bitcoin/mm2TEST/bitcoin.conf.mm2TEST /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.mm2TEST

rsync /home/sacarlson/.bitcoin/mm3TEST/bitcoin.conf.mm3TEST /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.mm3TEST
rsync /home/sacarlson/.bitcoin/mm3TEST/bitcoin.conf.mm3TEST sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/mm3TEST/bitcoin.conf.mm3TEST
#rsync /home/sacarlson/.bitcoin/mm3TEST/bitcoin.conf.mm3TEST /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.mm3TEST

rsync /home/sacarlson/.bitcoin/mm4TEST/bitcoin.conf.mm4TEST /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.mm4TEST
rsync /home/sacarlson/.bitcoin/mm4TEST/bitcoin.conf.mm4TEST sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/mm4TEST/bitcoin.conf.mm4TEST
#rsync /home/sacarlson/.bitcoin/mm4TEST/bitcoin.conf.mm4TEST /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.mm4TEST


rsync /home/sacarlson/.bitcoin/ixcoin/bitcoin.conf.ixcoin /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.ixcoin
rsync /home/sacarlson/.bitcoin/ixcoin/bitcoin.conf.ixcoin sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/ixcoin/bitcoin.conf.ixcoin
rsync /home/sacarlson/.bitcoin/ixcoin/bitcoin.conf.ixcoin /home/sacarlson/bitcoin/multicoin-exp/doc/bitcoin.conf.ixcoin
rsync /home/sacarlson/.bitcoin/ixcoin/bitcoin.conf.ixcoin /home/sacarlson/bitcoin/multicoin-qt/doc/bitcoin.conf.ixcoin
rsync /home/sacarlson/.bitcoin/ixcoin/bitcoin.conf.ixcoin /home/sacarlson/bitcoin/multicoin/doc/bitcoin.conf.ixcoin

rsync /home/sacarlson/.bitcoin/i0coin/bitcoin.conf.i0coin sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/i0coin/bitcoin.conf.i0coin
rsync /home/sacarlson/.bitcoin/i0coin/bitcoin.conf.i0coin /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.i0coin

rsync /home/sacarlson/.bitcoin/geist/bitcoin.conf.giestgeld sacarlson@192.168.2.158:/home/sacarlson/.bitcoin/geist/bitcoin.conf.giestgeld
rsync /home/sacarlson/.bitcoin/geist/bitcoin.conf.giestgeld /var/www/exchange.beertokens.info/www/docs/multicoin/bitcoin.conf.giestgeld


#rsync -a /home/sacarlson/bitcoin/piotrnar/bitcoin/src/bitcoind sacarlson@192.168.2.158:/home/sacarlson/multicoind

#rsync -a /home/sacarlson/bitcoin/multicoin-x3/src/bitcoind sacarlson@192.168.2.158:/home/sacarlson/multicoind
#rsync -a /home/sacarlson/bitcoin/multicoin-x3/src/bitcoind /home/sacarlson/multicoind

rsync -a /home/sacarlson/bitcoin/multicoin-exp/src/bitcoind /home/sacarlson/multicoind 
strip -s /home/sacarlson/multicoind
rsync -a /home/sacarlson/multicoind sacarlson@192.168.2.158:/home/sacarlson/multicoind
rsync -a /home/sacarlson/multicoind /var/www/exchange.beertokens.info/www/docs/multicoin/multicoind

strip -s /home/sacarlson/bitcoin/i0coin/i0coin/src/i0coind
rsync -a /home/sacarlson/bitcoin/i0coin/i0coin/src/i0coind /home/sacarlson/i0coind
rsync -a /home/sacarlson/bitcoin/i0coin/i0coin/src/i0coind sacarlson@192.168.2.158:/home/sacarlson/i0coind



